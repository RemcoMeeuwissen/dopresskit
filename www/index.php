<?php


spl_autoload_register(function ($class)
{
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (is_file(__DIR__ . '/' . $filename)) {
        require_once(__DIR__ . '/' . $filename);
    }
});


/* @var $config \Presskit\Config */
$config   = require(__DIR__.'/index.config.php');
$presskit = true;
try
{
	$presskit = new Presskit\Presskit($config, $_GET, $_POST, $_REQUEST);
	switch($presskit->getRequest())
	{
		case \Presskit\Request::REQUEST_COMPANY_PAGE:
		{
			Presskit\TranslateTool::$languageDir = $config->languageDirname;
			echo $presskit->output(
				$presskit->parseCompany($config->dataXmlFilename),
				$config->templateCompanyPhpFilename,
				$config->currentDir
					.'/index'
					.($presskit->getAvailableLanguages() > 1
						? '-'.$presskit->getCurrentLanguage()
						: ''
					 )
					.'.html'
			);
			exit;
			break;
		}
		case \Presskit\Request::REQUEST_RELEASE_PAGE:
		{
			Presskit\TranslateTool::$languageDir = $config->languageDirname;
			$companyContent = $presskit->parseCompany($config->dataXmlFilename);

			$release = $presskit->getRequestRelease();
			Presskit\TranslateTool::$languageDir = $config->currentDir
				.$release['dir']
				.DIRECTORY_SEPARATOR
				.$presskit->getRelativePath($config->languageDirname);

			if (is_readable($config->currentDir.$release['dir'].DIRECTORY_SEPARATOR.'index.config.php'))
			{
				$releaseConfig = require($config->currentDir.$release['dir'].DIRECTORY_SEPARATOR.'index.config.php');
				if ($releaseConfig->cssFilenames && !empty($releaseConfig->cssFilenames))
				{
					$config->cssFilenames = array_unique(
						array_merge(
							$config->cssFilenames,
							$releaseConfig->cssFilenames
						)
					);
				}
				if ($releaseConfig->skipEmpty && !empty($releaseConfig->skipEmpty))
				{
					$config->skipEmpty = array_unique(
						array_merge(
							$config->skipEmpty,
							$releaseConfig->skipEmpty
						)
					);
				}
				if ($releaseConfig->autoCreateStaticHtml !== null && is_bool($releaseConfig->autoCreateStaticHtml))
				{
					$config->autoCreateStaticHtml = $releaseConfig->autoCreateStaticHtml;
				}
				if ($releaseConfig->googleAnalytics && trim($releaseConfig->googleAnalytics) != '')
				{
					$config->googleAnalytics = $releaseConfig->googleAnalytics;
				}
				if ($releaseConfig->pressRequestCopy && is_array($releaseConfig->pressRequestCopy) && count($releaseConfig->pressRequestCopy) > 0)
				{
					$tmp = $config->pressRequestCopy;
					if (!isset($tmp) || !is_array($tmp))
					{
						$tmp = array();
						$config->pressRequestCopy = $tmp;
					}
					$config->pressRequestCopy = \Presskit\Config::array_merge_recursive_distinct(
						$config->pressRequestCopy,
						$releaseConfig->pressRequestCopy
					);
					unset($tmp);
				}
				$config->imageIconFilename    = $releaseConfig->relativePath($releaseConfig->imageIconFilename);
				$config->imageLogoFilename    = $releaseConfig->relativePath($releaseConfig->imageLogoFilename);
				$config->imageLogoZipFilename = $releaseConfig->relativePath($releaseConfig->imageLogoZipFilename);
			}

			// ugly hack to modify "currentDir" to: "currentDir/releaseDir"!
			// REMEMBER: all $config->currentDir is now pointed into release-page! (so: Presskit\Install reflect it!)
			$prop = new ReflectionProperty($config, 'currentDir');
			$prop->setAccessible(true);
			$prop->setValue($config, $config->currentDir.DIRECTORY_SEPARATOR.$release['dir'].DIRECTORY_SEPARATOR);
			$prop->setAccessible(false);

			$content = $presskit->parseRelease($config->dataXmlFilename, $release['dir']);

			$content->setCompany($companyContent);
			$content->setAdditionalInfo(array_merge(
				(array)$content->getAdditionalInfo(),
				array('release_name' => $release['dir']),
				array('pressRequestCopy' => $config->pressRequestCopy)
			));

			if ($content->getMonetization() == Presskit\Content\SharedContent::MONETIZATION_SHARED_COMPANY)
			{
				$content->setMonetization($companyContent->getMonetization());
			}

			echo $presskit->output(
				$content,
				$config->templateReleasePhpFilename,
				$config->currentDir
					.'/index'
					.($presskit->getAvailableLanguages() > 1
						? '-'.$presskit->getCurrentLanguage()
						: ''
					 )
					.'.html'
			);
			exit;

			break;
		}
		case \Presskit\Request::REQUEST_CREDITS_PAGE:
		{
			Presskit\TranslateTool::$languageDir = $config->languageDirname;
			Presskit\TranslateTool::loadLanguage(
				$config->languageDirname,
				$presskit->getCurrentLanguage()
			);

			echo $presskit->output(
				array(
					'config'   => $config,
					'presskit' => $presskit,
				),
				$config->templateCreditsPhpFilename,
				$config->currentDir.DIRECTORY_SEPARATOR
					.\Presskit\Request::REQUEST_CREDITS_PAGE
					.DIRECTORY_SEPARATOR.'index-en.html',
				true
			);
			exit;
			break;
		}
		default:
		{
			throw new Exception('Invalid request!');
			break;
		}
	}
}
catch (Presskit\Exceptions\DataXmlFilenameMissingException $e)
{
	try
	{
		$presskitInstall = new Presskit\Install($config);
		switch($e->getCode())
		{
			case Presskit\Exceptions\DataXmlFilenameMissingException::CODE_COMPANY:
			{
				$presskitInstall->installCompany();
				break;
			}
			case Presskit\Exceptions\DataXmlFilenameMissingException::CODE_RELEASE:
			{
				$presskitInstall->installRelease($presskit->getRequestRelease());
				break;
			}
			default:
			{
				throw $e;
				break;
			}
		}
		header('Location: '.$_SERVER['REQUEST_URI']);
		exit;
	}
	catch(\Exception $e)
	{
		throw $e;
	}
}
catch (Presskit\Exceptions\ReleaseNotFoundException $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	include_once($config->template404PhpFilename);
	exit;
}
catch (Presskit\Exceptions\ReleaseNameIsMissingCreateSome $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	$errorMessages = array(
		get_class($e),
		implode('.<br/>', explode('. ', $e->getMessage()))
	);
	include_once($config->template404PhpFilename);
	exit;
}
