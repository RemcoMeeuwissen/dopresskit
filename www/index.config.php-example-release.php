<?php

{
	$releaseConfig = new \Presskit\Config(__DIR__);


	// if <press-can-request-copy>true</press-can-request-copy>
	// - then you can put here formUrl and/or hidden-fields
	$tmp = $releaseConfig->pressRequestCopy;
	$tmp[\Presskit\Config::PressRequestCopy_formHidden] = array(
		'source' => 'press-kit:'.basename(__DIR__),
	);
	$releaseConfig->pressRequestCopy = $tmp;

	$releaseConfig->imageIconFilename = basename($config->imagesDirname).'/logos/shark_ico.png';
	$releaseConfig->imageLogoFilename = basename($config->imagesDirname).'/logos/planetGula-logo800x550.png';
	$releaseConfig->imageLogoZipFilename = basename($config->imagesDirname).'/logos.zip';


	return $releaseConfig;
}
