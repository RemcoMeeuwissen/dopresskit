<?php

namespace Presskit;


class Config
{
	const PressRequestCopy_formUse       = 'formUse';
	const PressRequestCopy_formHidden    = 'formHidden';
	const PressRequestCopy_phpForm       = 'phpUrl';
	const PressRequestCopy_mailchimpForm = 'mailchimpUrl';

	private $currentDir;

	private $dataXmlFilename      = 'data.xml';
	private $cssFilenames         = array('index.css');
	private $trailersDirname      = 'trailers';
	private $languageDirname      = 'lang';
	private $imagesDirname        = 'images';
	private $imageHeaderFilename  = 'images/header.png';
	private $imageZipFilename     = 'images/images.zip';
	private $imageLogoZipFilename = 'images/logo.zip';
	private $imageLogoFilename    = 'images/logo.png';
	private $imageIconFilename    = 'images/icon.png';
	/** array (it's possible to exclude filename or REGEX) */
	private $companyExcludeImageNames = array(
		'logo.png',
		'icon.png',
		'header.png',
	);
	/** array (it's possible to exclude filename or REGEX) */
	private $releaseExcludeImageNames = array(
		'logo.png',
		'icon.png',
		'header.png',
	);
	/** array (it's possible to exclude dirname or REGEX) */
	private $releaseExcludeDirs = array(
		'.',
		'..',
		'Presskit',
		'images',
		'lang',
		'trailers',
		\Presskit\Request::REQUEST_CREDITS_PAGE,
	);
	/** true if you want to've nice-url, otherwise false. */
	public static $isModRewriteEnabled = false;
	private $googleAnalytics = null;
	private $pressRequestCopy = array();
	private $skipEmpty = array();
	private $autoCreateStaticHtml = false;

	private $templateCompanyDataXmlFilename = 'Presskit/Templates/company.xml';
	private $templateCompanyLangXmlFilename = 'Presskit/Templates/company.lang.xml';
	private $templateCompanyPhpFilename     = 'Presskit/Templates/company.php';
	private $templateReleaseDataXmlFilename = 'Presskit/Templates/release.xml';
	private $templateReleaseLangXmlFilename = 'Presskit/Templates/release.lang.xml';
	private $templateReleasePhpFilename     = 'Presskit/Templates/release.php';
	private $templateCreditsPhpFilename     = 'Presskit/Templates/credits.php';
	private $template404PhpFilename         = 'Presskit/Templates/404.php';


	public function __construct($currentDir)
	{
		if (!is_readable($currentDir))
		{
			throw new Exception('Invalid $currentDir = '.$currentDir);
		}
		if (substr($currentDir, -1) != DIRECTORY_SEPARATOR)
		{
			$currentDir .= DIRECTORY_SEPARATOR;
		}
		$this->currentDir = $currentDir;
	}

	public function __set($name, $value)
	{
		if ($name == 'currentDir')
		{
			throw new \Exception('Invalid $name! currentDir is reserved!');
		}
		if (!is_array($value) && in_array($name,
				array(
					'companyExcludeImageNames',
					'skipEmpty',
				)
		   ))
		{
			throw new \Exception('Invalid '.$name.'! It needs to be an array!');
		}
		$this->$name = $value;
	}

	public function __get($name)
	{
		if (isset($this->$name))
		{
			$prefix = '';
			$suffix = '';
			if (preg_match('/(Filename|Dirname)$/i', $name, $tmp))
			{
				if (preg_match('/^(template).+$/i', $name))
				{
					$prefix = realpath(__DIR__.DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR;
				}
				else
				{
					$prefix = $this->currentDir;
				}
				$suffix = (strtolower($tmp[1]) == 'dirname'
					? DIRECTORY_SEPARATOR
					: ''
				);
			}
			if (is_array($this->$name))
			{
				return $this->$name;
			}
			else
			{
				return $prefix.$this->$name.$suffix;
			}
		}
		else
		{
			$ref = new \ReflectionClass($this);
			foreach($ref->getProperties(\ReflectionProperty::IS_PRIVATE) as $prop)
			{
				if ($prop->getName() === $name)
				{
					return $this->$name;
				}
			}
			throw new \Exception('Uninitialized variable '.$name);
		}
	}

	public function relativePath($absoluteResource)
	{
		return str_replace(
			$this->currentDir,
			'',
			$absoluteResource
		);
	}

	public function hasSkipEmpty($key)
	{
		return in_array($key, $this->skipEmpty);
	}

	/**
	 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
	 * keys to arrays rather than overwriting the value in the first array with the duplicate
	 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
	 * this happens (documented behavior):
	 *
	 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('org value', 'new value'));
	 *
	 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
	 * Matching keys' values in the second array overwrite those in the first array, as is the
	 * case with array_merge, i.e.:
	 *
	 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('new value'));
	 *
	 * Parameters are passed by reference, though only for performance reasons. They're not
	 * altered by this function.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
	 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
	 */
	public static function array_merge_recursive_distinct(array $array1, array $array2)
	{
		$merged = $array1;

		foreach ( $array2 as $key => $value )
		{
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
			{
				$merged [$key] = self::array_merge_recursive_distinct ( $merged [$key], $value );
			}
			else
			{
				$merged [$key] = $value;
			}
		}

		return $merged;
	}
}
