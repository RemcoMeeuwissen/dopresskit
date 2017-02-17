<?php

{
	$config = new \Presskit\Config(__DIR__);

	// mod_rewite: uncomment below, if u need it...
	$config::$isModRewriteEnabled = true;

	// GoogleAnalytics: uncomment below, if u need it...
	$config->googleAnalytics = 'UA-12345678-1';

	// if <press-can-request-copy>true</press-can-request-copy>
	// - then you can put here formUrl and/or hidden-fields
	$tmp = $config->pressRequestCopy;
	$tmp[\Presskit\Config::PressRequestCopy_formUse] = \Presskit\Config::PressRequestCopy_mailchimpForm;
	$tmp[\Presskit\Config::PressRequestCopy_phpForm] = '//domain.tld/subscribers.php';
	$tmp[\Presskit\Config::PressRequestCopy_mailchimpForm] = '//whateverMailchimpSingUpFormUrl.us15.list-manage.com/subscribe/post?u=1234567890abcdef&amp;id=123456789';
	$tmp[\Presskit\Config::PressRequestCopy_formHidden] = array(
		'source' => 'press-kit:company',
	);
	$config->pressRequestCopy = $tmp;


	$config->imageIconFilename = basename($config->imagesDirname).'/sjet-logos/sjet-logo-without-text.png';
	$config->imageLogoFilename = basename($config->imagesDirname).'/sjet-logos/sjet-logo-with-text.png';
	$config->imageLogoZipFilename = basename($config->imagesDirname).'/sjet-logos.zip';
	$config->imageHeaderFilename = 'images/sjet-logo4pressKit.jpg';

	// companyExcludeImageNames
	$tmp = $config->companyExcludeImageNames;
	$tmp[] = '^sjet\-logo.*';
	$config->companyExcludeImageNames = $tmp;

	// excludeReleaseDirNames
	$tmp = $config->releaseExcludeDirs;
//	$tmp[] = '^planet*';
	$config->releaseExcludeDirs = $tmp;

	// css
	$tmp = $config->cssFilenames;
	$tmp[] = 'sjet.css';
//	$tmp[] = '//fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic&subset=latin,latin-ext';
	$config->cssFilenames = $tmp;

	// skipEmpty(~when you want to skip section if its empty!), eg: imagesDirname
	$tmp = $config->skipEmpty;
	$tmp[] = 'images.company';
	$tmp[] = 'images.release';
	$config->skipEmpty = $tmp;

	// for auto-create: index.html (~index-{langs}.html) files...
	$config->autoCreateStaticHtml = true;

	return $config;
}
