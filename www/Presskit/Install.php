<?php

namespace Presskit;


class Install
{
	private $config;


	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function installCompany()
	{
		if (!is_readable($this->config->dataXmlFilename))
		{
			$this->copy(
				$this->config->templateCompanyDataXmlFilename,
				Helpers::dataXmlToLanguageDataXml(
					$this->config->dataXmlFilename,
					strtolower(TranslateTool::getDefaultLanguage()),
					false
				)
			);
		}
		if (!is_readable($this->config->imagesDirname))
		{
			mkdir($this->config->imagesDirname);
		}
		if (!is_readable($this->config->trailersDirname))
		{
			mkdir($this->config->trailersDirname);
		}
		$this->installLanguage(
			$this->config->languageDirname,
			$this->config->templateCompanyLangXmlFilename
		);
	}

	public function installRelease(array $release)
	{
		if (
			   !$release || empty($release) || !is_array($release)
			|| !isset($release['dir'])
		   )
		{
			throw new \Exception('Unable to installRelease!');
		}

		$currentDir = $this->config->currentDir;
		if (basename($this->config->currentDir) != $release['dir'])
		{
			$currentDir .= $release['dir'].DIRECTORY_SEPARATOR;
		}
		$currentDir = realpath($currentDir).DIRECTORY_SEPARATOR;
		$dataXml    = Helpers::dataXmlToLanguageDataXml(
			$currentDir.basename($this->config->dataXmlFilename),
			strtolower(TranslateTool::getDefaultLanguage()),
			false
		);
		if (!is_readable($dataXml))
		{
			$this->copy(
				$this->config->templateReleaseDataXmlFilename,
				$dataXml
			);
		}
		if (!is_readable($currentDir.basename($this->config->imagesDirname)))
		{
			mkdir($currentDir.basename($this->config->imagesDirname));
		}
		if (!is_readable($currentDir.basename($this->config->trailersDirname)))
		{
			mkdir($currentDir.basename($this->config->trailersDirname));
		}
		$this->installLanguage(
			$currentDir.basename($this->config->languageDirname).DIRECTORY_SEPARATOR, // @see: Presskit\Config::getRelativePath()
			$this->config->templateReleaseLangXmlFilename
		);
	}

	protected function installLanguage($languageDir, $sourceLangXmlFilename)
	{
		if (!is_readable($languageDir))
		{
			mkdir($languageDir);
		}
		$langFilename = $languageDir.$this->getInstallLanguageId().'-'.$this->getInstallLanguageName().'.xml';
		if (!is_readable($langFilename))
		{
			$this->copy(
				$sourceLangXmlFilename,
				$langFilename
			);
		}
	}

	private function getInstallLanguageId()
	{
		return TranslateTool::getDefaultLanguage();
	}

	private function getInstallLanguageName()
	{
		$languages = TranslateTool::getLanguages();
		return $languages[$this->getInstallLanguageId()];
	}

	protected function copy($source, $target)
	{
		if (!is_readable($source))
		{
			throw new \Exception(
				'Unable to copy! source is missing!'
				.' path: '.$source
			);
		}
		copy($source, $target);
	}
}
