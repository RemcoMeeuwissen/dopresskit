<?php

require_once(__DIR__ . '/autoload.php');

$presskit = new Presskit\Presskit;
$content = $presskit->parse(__DIR__ . '/data.xml');

// Language logic

include 'lang/TranslateTool.php';
$language = TranslateTool::loadLanguage(isset($_GET['l']) ? $_GET['l'] : null, 'index.php');
$languageQuery = ($language != TranslateTool::getDefaultLanguage() ? '?l='. $language : '');

if (file_exists('data-'. $language .'.xml'))
	$xml = simplexml_load_file('data-'. $language .'.xml');
else
	$xml = simplexml_load_file('data.xml');

foreach( $xml->children() as $child )
{
	switch( $child->getName() )
	{
		case("analytics"):
			define("ANALYTICS", $child);
			break;
		case("additionals"):
			$additionals = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$additionals[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;					
		case("credits"):
			$credits = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$credits[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;					
		case("contacts"):
			$contacts = array();
			$i = 0;
			foreach( $child->children() as $subchild )
			{
				$contacts[$i][$subchild->getName()] = $subchild;
				$i++;
			}
			break;					
	}
}

function parseLink($uri)
{
    $parsed = trim($uri);
    if( strpos($parsed, "http://") === 0 )
        $parsed = substr($parsed, 7);
    if (strpos($parsed, "https://") === 0 )
        $parsed = substr($parsed, 8);
    if( strpos($parsed, "www.") === 0 )
        $parsed = substr($parsed, 4);
    if( strrpos($parsed, "/") == strlen($parsed) - 1)
        $parsed = substr($parsed, 0, strlen($parsed) - 1);
    if( substr($parsed,-1,1) == "/" )
    	$parsed = substr($parsed, 0, strlen($parsed) - 1);

    return $parsed;
}

$languages = TranslateTool::getLanguages();

$company = array(
	'releases' => array(),
	'images_archive_size' => 0,
	'images' => array(),
	'logo_archive_size' => 0,
	'logo' => NULL,
	'icon' => NULL,
	'additional_links' => array(),
	'credits' => array(),
	'contacts' => array(),
	'google_analytics' => NULL,
);

$defaultDirectories = array(
	'.',
	'..',
	'lang',
	'images',
	'trailers',
);

$dir = new DirectoryIterator(dirname(__FILE__));
foreach ($dir as $file) {
	if ($file->isDir()) {
		$filename = $file->getFilename();
		$filenameStart = substr($filename, 0 , 1);

		if (! in_array($filename, $defaultDirectories) && $filenameStart !== '_' && $filenameStart !== '.') {
			$url = 'sheet.php?p=' . $filename;
			if ($language !== TranslateTool::getDefaultLanguage()) {
				$url .= '&l=' . $language;
			}

			$company['releases'][] = array(
				'name' => ucwords(str_replace('_', ' ', $filename)),
				'url' => $url,
			);
		}
	}
}

if (file_exists('images/images.zip')) {
	$file = new SplFileInfo('images/images.zip');
	$bytes = $file->getSize();

	$units = ['B', 'KB', 'MB'];

	for ($unit = 0; $bytes > 1024 && $unit < (count($units) - 1); $unit++) {
		$bytes /= 1024;
	}

	$bytes = round($bytes, 2);
	$company['images_archive_size'] = $bytes . ' ' . $units[$unit];
}

$dir = new DirectoryIterator(dirname(__FILE__) . '/images');
foreach ($dir as $file) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);

	if ($file->isFile()) {
		$info = new finfo;
		$mimeType = $info->file($file->getPathname(), FILEINFO_MIME);

		if (substr($mimeType, 0, 5) === 'image') {
			$filename = $file->getFilename();

			if (substr($filename, 0, 4) !== 'logo' && substr($filename, 0, 4) !== 'icon' && substr($filename, 0, 6) !== 'header') {
				$company['images'][] = $file->getFilename();
			}
		}
	}
}

if (file_exists('images/logo.zip')) {
	$file = new SplFileInfo('images/logo.zip');
	$bytes = $file->getSize();

	$units = ['B', 'KB', 'MB'];

	for ($unit = 0; $bytes > 1024 && $unit < (count($units) - 1); $unit++) {
		$bytes /= 1024;
	}

	$bytes = round($bytes, 2);
	$company['logo_archive_size'] = $bytes . ' ' . $units[$unit];
}

if (file_exists('images/logo.png')) {
	$company['logo'] = 'logo.png';
}

if (file_exists('images/icon.png')) {
	$company['icon'] = 'icon.png';
}

foreach ($additionals as $additional) {
	$urlName = parseLink($additional['additional']->link);

	if (strpos($urlName, '/') !== false) {
		$urlName = substr($urlName, 0, strpos($urlName, '/'));
	}

	$company['additional_links'][] = array(
		'title' => $additional['additional']->title,
		'description' => $additional['additional']->description,
		'url' => 'http://'.parseLink($additional['additional']->link),
		'urlName' => $urlName, 
	);
}

foreach ($credits as $credit) {
	$url = NULL;

	if (count($credit['credit']->website) !== 0) {
		$url = 'http://'.parseLink($credit['credit']->website).'/';
	}

	$company['credits'][] = array(
		'name' => $credit['credit']->person,
		'role' => $credit['credit']->role,
		'url' => $url,
	);
}

foreach ($contacts as $contact) {
	$arr = array(
		'name' => $contact['contact']->name,
		'url' => NULL,
		'urlName' => NULL,
		'email' => NULL,
	);

	if (count($contact['contact']->link) !== 0) {
		$arr['url'] = 'http://'.parseLink($contact['contact']->link);
		$arr['urlName'] = parseLink($contact['contact']->link);
	}

	if (count($contact['contact']->mail) !== 0) {
		$arr['email'] = $contact['contact']->mail;
	}

	$company['contacts'][] = $arr;
}

//if (defined("ANALYTICS") && strlen(ANALYTICS) > 10 )
if (defined('ANALYTICS')) {
	$company['google_analytics'] = ANALYTICS;
}

include('./_templates/company.php');