<?php

{
	$config = new Presskit\Config(__DIR__);

	// mod_rewite: uncomment below, if u need it...
//	$config::$isModRewriteEnabled = true;

	// GoogleAnalytics: uncomment below, if u need it...
//	$config->googleAnalytics[Presskit\Request::REQUEST_COMPANY_PAGE] = 123456789;
//	$config->googleAnalytics['release-name'] = 123456789;

    // companyExcludeImageNames
//    $tmp = $config->companyExcludeImageNames;
//    $tmp[] = '^my\-logo.*';
//    $config->companyExcludeImageNames = $tmp;

	// css
//	$tmp = $config->cssFilenames;
//	$tmp[] = '//fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic&subset=latin,latin-ext';
//	$config->cssFilenames = $tmp;

	return $config;
}
