<?php

namespace Presskit;


class Request
{
	const REQUEST_COMPANY_PAGE = 'company';
	const REQUEST_RELEASE_PAGE = 'release';
	const REQUEST_CREDITS_PAGE = 'credits';

	private $config;
	private $get;
	private $post;
	private $request;
	private $currentRequest;
	private $defaultRequest;
	private $reInvestigateRequest = true;
	private $releases;
	private $currentRelease;
	private $currentLanguage;


	public function __construct(
		Config $config,
		array $releases,
		$phpGet = null,
		$phpPost = null,
		$phpRequest = null
	)
	{
		if (empty($phpGet)) { $phpGet = $_GET; }
		if (empty($phpPost)) { $phpPost = $_POST; }
		if (empty($phpRequest)) { $phpRequest = $_REQUEST; }

		if (!is_array($phpGet)) { throw new Exception('phpGet isn\'t array!'); }
		if (!is_array($phpPost)) { throw new Exception('phpPost isn\'t array!'); }
		if (!is_array($phpRequest)) { throw new Exception('phpRequest isn\'t array!'); }

		$this->config = $config;
		$this->releases = $releases;
		$this->get = $phpGet;
		$this->post = $phpPost;
		$this->request = $phpRequest;

		$this->investigateRequest();
	}

	public function setDefaultRequest($defaultRequest)
	{
		$this->defaultRequest = $defaultRequest;
		$this->reInvestigateRequest = true;
	}

	public function getRequest()
	{
		$this->investigateRequest();
		return $this->currentRequest;
	}

	public function getRequestRelease()
	{
		return $this->currentRelease;
	}

	protected function investigateRequest()
	{
		if (!$this->reInvestigateRequest)
		{
			return;
		}

		// Language
		$this->currentLanguage = (isset($_GET['l'])
			? basename(strtolower(trim($_GET['l'])))
			: TranslateTool::getDefaultLanguage()
		);


		// Page
		$page = (isset($this->request['p']) ? basename(trim($this->request['p'])) : $this->defaultRequest);

		if ($this->isRequestCreditsPage($page))
		{
			return ($this->currentRequest = self::REQUEST_CREDITS_PAGE);
		}

		$this->currentRelease = null;
		if (($this->currentRelease = $this->isRequestReleasePage($page)) !== false)
		{
			return ($this->currentRequest = self::REQUEST_RELEASE_PAGE);
		}

		$this->currentRequest = self::REQUEST_COMPANY_PAGE;

		$this->reInvestigateRequest = false;
	}

	protected function isRequestCreditsPage($page)
	{
		return (strtolower($page) == self::REQUEST_CREDITS_PAGE ? true : false);
	}

	protected function isRequestReleasePage($page)
	{
		if (!empty($this->releases) && !empty($page))
		{
			if (
				   isset($this->releases[strtolower($page)])
				&& is_dir($this->config->currentDir.$this->releases[strtolower($page)]['dir'])
				&& is_readable($this->config->currentDir.$this->releases[strtolower($page)]['dir'])
			   )
			{
				return $this->releases[strtolower($page)];
			}
			else
			{
				throw new Exceptions\ReleaseNotFoundException();
			}
		}
		else
		if (empty($this->releases) && !empty($page))
		{
			$names = array($page);
			$names[] = str_replace(' ', '-', ucwords(str_replace(array('_', '-'), ' ', $page)));
			$names[] = str_replace(' ', '_', ucwords(str_replace(array('_', '-'), ' ', $page)));
			sort($names);
			$names = array_unique($names);

			throw new Exceptions\ReleaseNameIsMissingCreateSome(
				'Requested releaseName "'.  htmlspecialchars($page).'" is missing!'
				.' And it looks like you\'ve fresh installation.'
				.' So create some sub-directory, like: "'
					.' '.implode('" or "', $names).'".'
				.' And refresh!'
			);
		}
		else
		{
			return false;
		}
	}

	public function getCurrentLanguage()
	{
		return $this->currentLanguage;
	}
}
