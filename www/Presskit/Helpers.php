<?php

namespace Presskit
{
	class Helpers
	{
		static public function tl($text)
		{
			$args = func_get_args();
			$args = array_slice($args, 1);
			return TranslateTool::translate('default', $text, $args, false);
		}

		static public function tlSet($set, $text)
		{
			$args = func_get_args();
			$args = array_slice($args, 2);
			return TranslateTool::translate($set, $text, $args, false);
		}

		static public function tlHtml($text)
		{
			$args = func_get_args();
			$args = array_slice($args, 1);
			return TranslateTool::translate('default', $text, $args, true);
		}

		static public function url($normalUrl, $rewriteUrl)
		{
			if (Config::$isModRewriteEnabled)
			{
				return $rewriteUrl;
			}
			else
			{
				return $normalUrl;
			}
		}

		static public function parseLink($uri)
		{
			$parsed = trim($uri);
			if( strpos($parsed, "http://") === 0 )
			{
				$parsed = substr($parsed, 7);
			}
			if (strpos($parsed, "https://") === 0 )
			{
				$parsed = substr($parsed, 8);
			}
			if( strpos($parsed, "www.") === 0 )
			{
				$parsed = substr($parsed, 4);
			}
			if( strrpos($parsed, "/") == strlen($parsed) - 1)
			{
				$parsed = substr($parsed, 0, strlen($parsed) - 1);
			}
			if( substr($parsed,-1,1) == "/" )
			{
				$parsed = substr($parsed, 0, strlen($parsed) - 1);
			}
			return $parsed;
		}

		static function dataXmlToLanguageDataXml($dataXmlFilename, $languageId, $throwExceptionWhenIsMissing = true)
		{
			$languageDataXml  = dirname($dataXmlFilename);
			list($ext, $filename) = explode('.', strrev(basename($dataXmlFilename)));

			$defaultLangDataXml = $languageDataXml
				.DIRECTORY_SEPARATOR
				.strrev($filename).'-'.strtolower(TranslateTool::getDefaultLanguage())
				.'.'.strrev($ext);

			$languageDataXml .= DIRECTORY_SEPARATOR
				.strrev($filename).'-'.strtolower($languageId)
				.'.'.strrev($ext);

			if (!$throwExceptionWhenIsMissing)
			{
				return $languageDataXml;
			}

			if (is_readable($languageDataXml))
			{
				return $languageDataXml;
			}
			else
			if (is_readable($defaultLangDataXml))
			{
				return $defaultLangDataXml;
			}
			else
			if (is_readable($dataXmlFilename))
			{
				return $dataXmlFilename;
			}
			else
			{
				throw new Exceptions\DataXmlFilenameMissingException();
			}
		}

		static public function filesizeToHumanReadable($filesize)
		{
			$units = ['B', 'KB', 'MB'];
			for ($unit = 0; $filesize >= 1024 && $unit < (count($units) - 1); $unit++)
			{
				$filesize /= 1024;
			}
			$filesize = round($filesize, 2);
			return $filesize . ' ' . $units[$unit];
		}

		static public function getFilesize($filename)
		{
			if (is_readable($filename))
			{
				$file = new \SplFileInfo($filename);
				return $file->getSize();
			}
			else
			{
				return 0;
			}
		}
	}
}

namespace
{
	function tl($text) { return call_user_func_array('\Presskit\Helpers::tl', func_get_args()); }
	function tlHtml($text) { return call_user_func_array('\Presskit\Helpers::tlHtml', func_get_args()); }
	function url($normalUrl, $rewriteUrl) { return call_user_func_array('\Presskit\Helpers::url', func_get_args()); }
	function parseLink($uri) { return call_user_func_array('\Presskit\Helpers::parseLink', func_get_args()); }
}
