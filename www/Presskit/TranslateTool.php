<?php

namespace Presskit;

// TODO: rewrite this class (~its static omg)!
class TranslateTool
{
	static protected $_translations;
	
	static protected $_translated;
	static protected $_untranslated;
	
	static protected $_language;
	static protected $_defaultLanguage;
	static protected $_languages;

	static public $languageDir = __DIR__;
	static public $prevLanguageDir = null;

	
	public static function getLanguages()
	{
		if (self::$languageDir != self::$prevLanguageDir)
		{
			self::$_languages = null;
			self::$prevLanguageDir = self::$languageDir;
		}

		if (!isset(self::$_languages))
		{
			$languages = array();
			if (
				   is_readable(self::$languageDir)
				&& $handle = opendir(self::$languageDir)
			   )
			{
				while (false !== ($entry = readdir($handle))) 
				{
					if (substr($entry,-4) == ".xml" )
					{
						$info = explode('-', substr($entry,0, -4), 2);
						$languages[$info[0]] = $info[1];
					}
				}
			}
			ksort($languages);
			$languages = array_merge(
				array(
					'en' => 'English',
				),
				$languages
			);
			
			self::$_languages = $languages;
			self::$_defaultLanguage = key($languages);
		}
		return self::$_languages;
	}

	public static function loadLanguage($languageDir, $languageId)
	{
		$languages = self::getLanguages();

		if (!isset($languages[$languageId]))
		{
			$language = self::$_defaultLanguage;
		}
		
		self::$_language = $languageId;
		if (
			   isset($languages[$languageId])
			&& is_readable(($languageFilename = $languageDir.$languageId.'-'.$languages[$languageId].'.xml'))
		   )
		{
			$xml = simplexml_load_file($languageFilename);
			self::$_translations = array();
			foreach ($xml as $set)
			{
				$setAttr = $set->attributes();
				$setName = isset($setAttr['name']) ? $setAttr['name'] : 'default';

//				if (!isset($setAttr['filename']) || $setAttr['filename'] == $file)
				{
					foreach ($set as $translation)
					{
						self::$_translations[(string)$setName][(string)$translation->base] = $translation->local;
					}			
				}		
			}
		}

		return self::$_language;
	}
	
	public static function getDefaultLanguage()
	{
		self::getLanguages();
		return self::$_defaultLanguage;
	}
	
	public static function translate($set, $text, $args = array(), $isHtml = false)
	{
		$defaultText = $text;
			
		$found = true;
		$direction = null;
		if (isset(self::$_translations[$set][$text]))
		{
			if (isset(self::$_translations[$set][$text]->attributes()->direction))
				$direction = self::$_translations[$set][$text]->attributes()->direction;
			
			$text = self::$_translations[$set][$text];
		}
		else if (isset(self::$_translations['default'][$text]))
		{
			if (isset(self::$_translations['default'][$text]->attributes()->direction))
				$direction = self::$_translations['default'][$text]->attributes()->direction;
			
			$text = self::$_translations['default'][$text];
		}
		else 
		{
			self::$_untranslated[$defaultText] = $text;
			$found = false;
		}
		self::$_translated[$defaultText] = $text;
		
		if (count($args) > 0)
		{
			$text = vsprintf($text, $args);
		}
		
		if (!$isHtml)
		{
			$text = htmlspecialchars($text);
		}
		
		if (!$found && self::$_language != 'en')
			$text = '<span style="color:red">' . $text .'</span>';
		
		if ($direction !== null)
			$text = '<span dir="'. $direction .'">'. $text .'</span>';
	
		return $text;
	}
	
	public static function makeBaseXml($untranslated = true)
	{
		$translations = $untranslated ? self::$_untranslated : self::$_translated;
		
		$xml = '';
		foreach ($translations as $base => $local)
		{
			if (strpos($base, '<') !== false)
				$base = '<![CDATA['. $base .']]>';
			else
				$base = htmlspecialchars($base);
			
			if (strpos($local, '<') !== false)
				$base = '<![CDATA['. $local .']]>';
			else
				$base = htmlspecialchars($local);
			
			$xml .= '		<translation>' ."\n";
			$xml .= '			<base>'. $base .'</base>' ."\n";
			$xml .= '			<local>'. $local .'</local>' ."\n";
			$xml .= '		</translation>' ."\n";
		}
	
		return $xml;
	}
}
