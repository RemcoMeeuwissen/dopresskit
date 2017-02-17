<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>PressKit: <?=$content->getTitle().($content->getCompany() ? ' by '.$content->getCompany()->getTitle() : '')?></title>
		<link href="//cdnjs.cloudflare.com/ajax/libs/uikit/1.2.0/css/uikit.gradient.min.css" rel="stylesheet" type="text/css">
		<?php if (count($content->getAdditionalInfo()->config->cssFilenames) > 0): ?>
		<?php foreach($content->getAdditionalInfo()->config->cssFilenames as $cssFilename): ?>
		<link href="/<?=$cssFilename?>" rel="stylesheet" type="text/css">
		<?php endforeach; ?>
		<?php endif; ?>
	</head>

	<body>
		<div class="uk-container uk-container-center">
			<div class="uk-grid">
				<div id="navigation" class="uk-width-medium-1-4">
					<h1 class="nav-header"><?=$content->getTitle()?></h1>

					<a class="nav-header" href="<?=
						\Presskit\Helpers::url(
							(count($content->getAdditionalInfo()->languages) > 1
								? '?l='.$content->getAdditionalInfo()->language
								: '/'
							),
							(count($content->getAdditionalInfo()->languages) > 1
								? $content->getAdditionalInfo()->language
								: '/'
							)
						)?>"><?=tl('press kit')?></a>

					<ul class="uk-nav uk-nav-side">
						<?php if (count($content->getAdditionalInfo()->languages) > 1): ?>
							<li class="language-select">
								<a>
									<?=tl('Language: ')?>
									<select onchange="document.location = '<?=
										\Presskit\Helpers::url(
											'?l=\' + this.value + \'&amp;p='.$content->getAdditionalInfo()->release_name,
											'/'.htmlspecialchars($content->getAdditionalInfo()->release_name)
												.'/index-\' + this.value + \'.html'
										)?>'">
										<?php foreach($content->getAdditionalInfo()->languages as $tag => $name): ?>
											<option value="<?=$tag?>" <?php if ($tag == $content->getAdditionalInfo()->language): ?>selected<?php endif; ?>><?= htmlspecialchars($name)?></option>
										<?php endforeach; ?>
									</select>
								</a>
							</li>
							<li class="uk-nav-divider"></li>
						<?php endif; ?>

						<li><a href="#factsheet"><?=tl('Factsheet')?></a></li>

						<?php if(!empty($content->getDescription())): ?>
							<li><a href="#description"><?=tl('Description')?></a></li>
						<?php endif; ?>

						<?php if ($content->getHistory() > 0): ?>
							<li><a href="#history"><?=tl('History')?></a></li>
						<?php endif; ?>

						<?php if (count($content->getTrailers()) !== 0 && !$content->hasSkipEmpty('trailers')): ?>
						<li><a href="#trailers"><?=tl('Videos')?></a></li>
						<?php endif; ?>

						<?php
							if (
									$content->getAdditionalInfo()->config->hasSkipEmpty('images.release')
								 && ($content->getAdditionalInfo()->images_archive_size == 0)
								 && count($content->getAdditionalInfo()->images) == 0
							   ):
						?>
						<?php else: ?>
						<li><a href="#images"><?=tl('Images')?></a></li>
						<?php endif; ?>

						<li><a href="#logo"><?=tl('Logo & Icon')?></a></li>

						<?php if (count($content->getAwards()) > 0): ?>
							<li><a href="#awards"><?=tl('Awards & Recognition')?></a></li>
						<?php endif; ?>

						<?php if (count($content->getQuotes()) > 0): ?>
							<li><a href="#quotes"><?=tl('Selected Articles')?></a></li>
						<?php endif; ?>

						<?php if ($content->canPressRequestCopy()): ?>
							<li><a href="#preview"><?=tl('Request Press Copy')?></a></li>
						<?php endif; ?>

						<?php if ($content->hasMonetization()): ?>
							<li><a href="#monetize"><?=tl('Monetization Permission')?></a></li>
						<?php endif; ?>

						<?php if (count($content->getAdditionalLinks()) > 0): ?>
							<li><a href="#links"><?=tl('Additional Links')?></a></li>
						<?php endif; ?>

						<li><a href="#about"><?=tl('About %s', $content->getCompany()->getTitle())?></a></li>

						<?php if (count($content->getCredits()) > 0): ?>
							<li><a href="#credits"><?=tl('Team')?></a></li>
						<?php endif; ?>

						<?php if (count($content->getCompany()->getContacts()) > 0): ?>
							<li><a href="#contact"><?=tl('Contact')?></a></li>
						<?php endif; ?>
					</ul>
				</div>

				<div id="content" class="uk-width-medium-3-4">
					<?php if ($content->getAdditionalInfo()->header): ?>
						<img src="<?=$content->getAdditionalInfo()->header?>" class="header" />
					<?php endif; ?>

					<div class="uk-grid">
						<div class="uk-width-medium-2-6">
							<h2 id="factsheet"><?=tl('Factsheet')?></h2>

							<p>
								<strong><?=tl('Developer:')?></strong><br/>
								<a href="<?=
									\Presskit\Helpers::url(
										(count($content->getAdditionalInfo()->languages) > 1
											? '?l='.$content->getAdditionalInfo()->language
											: '/'
										),
										(count($content->getAdditionalInfo()->languages) > 1
											? '/'.$content->getAdditionalInfo()->language
											: '/'
										)
									)?>"><?=$content->getCompany()->getTitle()?></a><br/>
								<?=tl('Based in %s', $content->getCompany()->getLocation())?>
							</p>

							<?php if (!empty($content->getReleaseDate())): ?>
							<p>
								<strong><?=tl('Release date:')?></strong><br/>
								<?=$content->getReleaseDate()?>.
							</p>
							<?php endif; ?>

							<?php if (count($content->getPlatforms()) > 0): ?>
							<p>
								<strong><?=tl('Platforms:')?></strong><br />

								<?php foreach($content->getPlatforms() as $platform): ?>
									<?php if ((string)$platform->url() != ''): ?>
										<a href="<?=$platform->url()?>"><?=$platform->name()?></a><br/>
									<?php elseif ((string)$platform->email() != ''): ?>
										<a href="mailto:<?=$platform->email()?>"><?=$platform->name()?></a><br/>
									<?php else: ?>
										<?=$platform->name()?><br/>
									<?php endif; ?>
								<?php endforeach; ?>
							</p>
							<?php endif; ?>

							<?php if (!empty($content->getWebsite())): ?>
							<p>
								<strong><?=tl('Website:')?></strong><br/>
								<a href="<?=$content->getWebsite()->url()?>" target="_blank"><?=$content->getWebsite()->name()?></a>
							</p>
							<?php endif; ?>

							<?php if (count($content->getPrices()) > 0): ?>
								<p>
									<strong><?=tl('Regular Price:')?></strong>
									<table>
										<?php foreach($content->getPrices() as $price): ?>
											<tr>
												<td style="text-align:right;"><?=$price->currency()?>&nbsp;</td>
												<td><?=$price->value()?></td>
											</tr>
										<?php endforeach; ?>
									</table>
								</p>
							<?php endif; ?>

							<?php if (count($content->getSocialContacts()) > 0): ?>
							<p>
								<strong><?=tl('Social:')?></strong><br/>
								<?php if ($content->getSocialContacts()[0]->name() == '@company'): ?>
								<?php $socialContacts = $content->getCompany()->getSocialContacts(); ?>
								<?php else: ?>
								<?php $socialContacts = $content->getSocialContacts(); ?>
								<?php endif; ?>
								<?php foreach($socialContacts as $contact): ?>
									<a href="<?=$contact->uri()?>"><?=$contact->name()?></a><br/>
								<?php endforeach; ?>
							</p>
							<?php endif; ?>
						</div>

						<div class="uk-width-medium-4-6">
							<h2 id="description"><?=tl('Description')?></h2>
							<p><?=$content->getDescription()?></p>

							<?php if ($content->getHistory() > 0): ?>
								<h2 id="history"><?=tl('History')?></h2>
								<?php foreach ($content->getHistory() as $history): ?>
									<?php if ($history->heading() != '' && $history->heading() != 'unused'): ?>
										<strong><?=$history->heading()?></strong>
									<?php endif; ?>
									<p><?=$history->body()?></p>
								<?php endforeach; ?>
							<?php endif; ?>

							<?php if (count($content->getFeatures()) > 0): ?>
							<h2><?=tl('Features')?></h2>
							<ul>
								<?php foreach ($content->getFeatures() as $feature): ?>
									<li><?=$feature?></li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
						</div>
					</div>

					<?php if (count($content->getTrailers()) === 0): ?>
						<?php if (!$content->hasSkipEmpty('trailers')): ?>
						<hr />
						<h2 id="trailers"><?=tl('Videos')?></h2>
						<p><?=tlHtml('There are currently no trailers available for %s. Check back later for more or <a href="#contact">contact us</a> for specific requests!', $content->getTitle())?></p>
						<?php endif; ?>
					<?php else: ?>
						<hr />
						<h2 id="trailers"><?=tl('Videos')?></h2>
						<?php foreach ($content->getTrailers() as $trailer): ?>
							<div id="trailers-inner" class="uk-alert">
								<strong id="trailer-name"><?=$trailer->name()?></strong>
								<br/><?=tl('Available:')?>
								<?php foreach ($trailer->locations() as $index => $location): ?>
									<?php if ((string) $location->format() === 'youtube'): ?>
										<a href="<?=$location?>" _target="_blank">Youtube</a><?php if ($index < (count($trailer->locations()) - 1)): ?>, <?php else: ?>.<?php endif; ?>
									<?php endif; ?>
									<?php if ((string) $location->format() === 'vimeo'): ?>
										<a href="<?=$location?>" _target="_blank">Vimeo</a><?php if ($index < (count($trailer->locations()) - 1)): ?>, <?php else: ?>.<?php endif; ?>
									<?php endif; ?>
									<?php if (in_array((string)$location->format(), \Presskit\Value\Trailer::getFileFormats())): ?>
										<a href="<?php

											if (is_readable($content->getAdditionalInfo()->config->trailersDirname.$location)): ?><?=

												$content->getAdditionalInfo()->config->relativePath($content->getAdditionalInfo()->config->trailersDirname).$location
												?><?php else: ?><?=$location
												?><?php endif;

											?>"><?=strtoupper((string)$location->format())?><?php

											if (is_readable($content->getAdditionalInfo()->config->trailersDirname.$location)): ?><?=
												'('
												.\Presskit\Helpers::filesizeToHumanReadable(
													\Presskit\Helpers::getFilesize(
														$content->getAdditionalInfo()->config->trailersDirname.$location)
													)
												.')'
												?><?php endif;

											?></a><?php if ($index < (count($trailer->locations()) - 1)): ?>, <?php else: ?>.<?php endif; ?>
									<?php endif; ?>
								<?php endforeach; ?>
								<?php if ((string) $trailer->youtube() !== ''): ?>
									<div class="uk-responsive-width iframe-container">
										<iframe src="<?=sprintf(
												\Presskit\Value\TrailerLocation::URL_YOUTUBE_EMBED,
												$trailer->youtube()
											)?>" frameborder="0" allowfullscreen></iframe>
									</div>
								<?php elseif ((string) $trailer->vimeo() !== ''): ?>
									<div class="uk-responsive-width iframe-container">
										<iframe src="<?=sprintf(
												\Presskit\Value\TrailerLocation::URL_YOUTUBE_EMBED,
												$trailer->vimeo()
											)?>" frameborder="0" allowfullscreen></iframe>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
						<div class="clear"></div>
					<?php endif; ?>


				<?php
					// images...
					if (
							$content->getAdditionalInfo()->config->hasSkipEmpty('images.release')
						 && ($content->getAdditionalInfo()->images_archive_size == 0)
						 && count($content->getAdditionalInfo()->images) == 0
					   ):
				?>
				<?php else: ?>
					<hr />
					<h2 id="images"><?=tl('Images')?></h2>
					<?php if ($content->getAdditionalInfo()->images_archive_size != 0): ?>
						<a href="<?=
							$content->getAdditionalInfo()->release_name
							.DIRECTORY_SEPARATOR
							.$content->getAdditionalInfo()->config->relativePath(
								$content->getAdditionalInfo()->config->imageZipFilename
							)?>"><div class="uk-alert"><?=tl('download all screenshots & photos as .zip (%s)', $content->getAdditionalInfo()->images_archive_size)?></div></a>
					<?php endif; ?>

					<?php if (count($content->getAdditionalInfo()->images) > 0): ?>
						<div class="uk-grid images">
							<?php foreach ($content->getAdditionalInfo()->images as $image): ?>
								<div class="uk-width-medium-1-2">
									<a href="<?=
										'/'
										.$content->getAdditionalInfo()->release_name
										.DIRECTORY_SEPARATOR
										.$content->getAdditionalInfo()->config->relativePath(
											$content->getAdditionalInfo()->config->imagesDirname
										)
										.$image?>"><img src="<?=
											'/'
											.$content->getAdditionalInfo()->release_name
											.DIRECTORY_SEPARATOR
											.$content->getAdditionalInfo()->config->relativePath(
												$content->getAdditionalInfo()->config->imagesDirname
											)
											.$image?>" alt="<?=$image?>" /></a>
								</div>
							<?php endforeach; ?>
						</div>
					<?php else: ?>
						<div class="uk-width-medium-1-1">
							<p class="images-text"><?=tlHtml('There are currently no screenshots available for %s. Check back later for more or <a href="#contact">contact us</a> for specific requests!', $content->getTitle())?></p>
						</div>
					<?php endif; ?>
				<?php endif; ?>

					<hr />
					<h2 id="logo"><?=tl('Logo & Icon')?></h2>
					<?php if ($content->getAdditionalInfo()->logo_archive_size != 0): ?>
						<a href="<?=
							'/'
							.$content->getAdditionalInfo()->release_name
							.DIRECTORY_SEPARATOR
							.$content->getAdditionalInfo()->config->relativePath(
								$content->getAdditionalInfo()->config->imageLogoZipFilename
							)?>"><div class="uk-alert"><?=tl('download logo files as .zip (%s)', $content->getAdditionalInfo()->logo_archive_size)?></div></a>
					<?php endif; ?>
					<div class="uk-grid images">
						<?php if (!empty($content->getAdditionalInfo()->logo)): ?>
							<div class="uk-width-medium-1-2"><a href="<?=
								'/'
								.$content->getAdditionalInfo()->release_name
								.DIRECTORY_SEPARATOR
								.$content->getAdditionalInfo()->logo?>"><img src="<?=

								'/'
								.$content->getAdditionalInfo()->release_name
								.DIRECTORY_SEPARATOR
								.$content->getAdditionalInfo()->logo?>" alt="logo" /></a></div>
						<?php endif; ?>

						<?php if (!empty($content->getAdditionalInfo()->icon)): ?>
							<div class="uk-width-medium-1-2"><a href="<?=
								'/'
								.$content->getAdditionalInfo()->release_name
								.DIRECTORY_SEPARATOR
								.$content->getAdditionalInfo()->icon?>"><img src="<?=

								'/'
								.$content->getAdditionalInfo()->release_name
								.DIRECTORY_SEPARATOR
								.$content->getAdditionalInfo()->icon?>" alt="icon" /></a></div>
						<?php endif; ?>
					</div>
					<?php if (empty($content->getAdditionalInfo()->logo) && empty($content->getAdditionalInfo()->icon)): ?>
						<p><?=tlHtml('There are currently no logos or icons available for %s. Check back later for more or <a href="#contact">contact us</a> for specific requests!', $content->getTitle())?></p>
					<?php endif; ?>

					<?php if (count($content->getAwards()) > 0): ?>
						<hr />
						<h2 id="awards"><?=tl('Awards & Recognition')?></h2>
						<ul>
							<?php foreach ($content->getAwards() as $award): ?>
								<li><?=$award->description()?><cite> <?=$award->award()?></cite></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if (count($content->getQuotes()) > 0): ?>
						<hr />
						<h2 id="quotes"><?=tl('Selected Articles')?></h2>
						<ul>
							<?php foreach ($content->getQuotes() as $quote): ?>
								<li>
									<?=$quote->description()?><br/>
									<cite>- <?=$quote->name()?><?php if ((string)$quote->website() !== ''): ?>,
										<a href="<?=$quote->website()->url()?>"><?=$quote->websiteName()?></a>
										<?php endif; ?>.
									</cite>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ($content->canPressRequestCopy()): ?>
						<hr />
						<h2 id="preview"><?=tl('Request Press Copy')?></h2>
						<p><?=tl("Please fill in your e-mail address below to complete a distribute() request and we'll get back to you as soon as a press copy is available for you.")?><br/>
						<div id="mailform">
						<?php
							$usePressKey = (
							   (
								   $content->getAdditionalInfo()->pressRequestCopy
								&& isset($content->getAdditionalInfo()->pressRequestCopy[\Presskit\Config::PressRequestCopy_formUse])
								&& isset($content->getAdditionalInfo()->pressRequestCopy[$content->getAdditionalInfo()->pressRequestCopy[\Presskit\Config::PressRequestCopy_formUse]])
							   )
									? $content->getAdditionalInfo()->pressRequestCopy[\Presskit\Config::PressRequestCopy_formUse]
									: null
							);
						?>
						<?php if ($usePressKey == \Presskit\Config::PressRequestCopy_phpForm): ?>
							<form id="pressrequest" class="uk-form" method="POST" action="<?=$content->getAdditionalInfo()->pressRequestCopy[$usePressKey]?>">
								<input type="email" id="email" name="email" placeholder="name@yourdomain.com" style="width:100%;" />
								<?php if ($content->getAdditionalInfo()->pressRequestCopy[\Presskit\Config::PressRequestCopy_formHidden] && is_array($content->getAdditionalInfo()->pressRequestCopy[\Presskit\Config::PressRequestCopy_formHidden])): ?>
									<?php foreach($content->getAdditionalInfo()->pressRequestCopy[\Presskit\Config::PressRequestCopy_formHidden] as $key => $value): ?>
									<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
									<?php endforeach; ?>
								<?php endif; ?>
								<input type="submit" class="uk-button" id="submit-button" value="<?=tl('request a press copy')?>" style="width:100%;" />
							</form>
							<p><?=tlHtml('Alternatively, you can always request a press copy by <a href="#contact">sending us a quick email</a>.')?></p>

						<?php elseif ($usePressKey == \Presskit\Config::PressRequestCopy_mailchimpForm): ?>
							<!-- Begin MailChimp Signup Form -->
							<link href="/index.mailchimp.classic-10_7.css" rel="stylesheet" type="text/css">
							<style type="text/css">
								#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
							</style>
							<div id="mc_embed_signup">
							<form action="<?=$content->getAdditionalInfo()->pressRequestCopy[$usePressKey]?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
								<?php if ($content->getAdditionalInfo()->pressRequestCopy[\Presskit\Config::PressRequestCopy_formHidden] && is_array($content->getAdditionalInfo()->pressRequestCopy[\Presskit\Config::PressRequestCopy_formHidden])): ?>
									<?php foreach($content->getAdditionalInfo()->pressRequestCopy[\Presskit\Config::PressRequestCopy_formHidden] as $key => $value): ?>
									<input type="hidden" name="<?=$key?>" value="<?=$value?>" id="mce-<?=$key?>" />
									<?php endforeach; ?>
								<?php endif; ?>
								<div id="mc_embed_signup_scroll">
									<div class="mc-field-group">
										<div>
											<div style="float:left;display:inline-block;width:78%;">
												<div style="float:left;display:inline-block;">
													<label for="mce-EMAIL"><strong>Email Address:</strong></label>
												</div>
												<div style="float:right;display:inline-block;width:50%;height:30px;text-align:right;">
													<div for="mce-EMAIL" class="mce_inline_error" style="display:none;"></div>
												</div>
												<div class="clear"></div>
												<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" />
												<div style="text-align:right;font-size:70%;">
													Powered by <a href="http://eepurl.com/cCmJ7v" title="MailChimp - email marketing made easy and fun" target="_blank">MailChimp</a>.
												</div>
											</div>
											<div style="float:right;display:inline-block;width:22%;margin-top:26px;text-align:center;">
												<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
											</div>
											<div class="clear"></div>
										</div>
										<div id="mce-responses" class="clear">
											<div class="response" id="mce-error-response" style="display:none;"></div>
											<div class="response" id="mce-success-response" style="display:none;"></div>
										</div>
									</div>
									<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
									<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_c9ef36bf4fb2f0117538f5068_ab93c30126" tabindex="-1" value=""></div>
								</div>
							</form>
							</div>
							<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
							<!--End mc_embed_signup-->
							<div><?=tlHtml('Alternatively, you can always request a press copy by <a href="#contact">sending us a quick email</a>.')?></div>

						<?php else: ?>
							<p><?=tlHtml('Alternatively, you can always request a press copy by <a href="#contact">sending us a quick email</a>.')?></p>
						<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ($content->hasMonetization()): ?>
						<hr />
						<h2 id="monetize"><?=tl('Monetization Permission')?></h2>
						<?php if ($content->getMonetization() == \Presskit\Content\ReleaseContent::MONETIZATION_FALSE): ?>
							<p><?=tl(
									'%s does currently not allow for the contents of %s to be published through video broadcasting services.',
									$content->getCompany()->getTitle(),
									$content->getTitle()
								)?></p>
						<?php elseif ($content->getMonetization() == \Presskit\Content\ReleaseContent::MONETIZATION_ASK): ?>
							<p><?=tl(
									'%s does allow the contents of this game to be published through video broadcasting services only with direct written permission from %s. Check at the bottom of this page for contact information.',
									$content->getCompany()->getTitle(),
									$content->getTitle()
								)?></p>
						<?php elseif ($content->getMonetization() == \Presskit\Content\ReleaseContent::MONETIZATION_NON_COMMERCIAL): ?>
							<p><?=tl(
									'%s allows for the contents of %s to be published through video broadcasting services for non-commercial purposes only. Monetization of any video created containing assets from %s is not allowed.',
									$content->getCompany()->getTitle(),
									$content->getTitle(),
									$content->getTitle()
								)?></p>
						<?php elseif ($content->getMonetization() == \Presskit\Content\ReleaseContent::MONETIZATION_MONETIZE): ?>
							<p><?=tl(
									'%s allows for the contents of %s to be published through video broadcasting services for any commercial or non-commercial purposes. Monetization of videos created containing assets from %s is legally & explicitly allowed by %s.',
									$content->getCompany()->getTitle(),
									$content->getTitle(),
									$content->getTitle(),
									$content->getCompany()->getTitle()
								)?> <?=tlHtml(
									'This permission can be found in writing at <a href="%s">%s</a>.',
									'//'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
									'//'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
								)?></p>
						<?php endif; ?>
					<?php endif; ?>

					<?php if (count($content->getAdditionalLinks()) > 0): ?>
						<hr />
						<h2 id="links"><?=tl('Additional Links')?></h2>
						<?php foreach ($content->getAdditionalLinks() as $additionaLink): ?>
							<p>
								<strong><?=$additionaLink->title()?></strong><br/>
								<?=$additionaLink->description()?> <a href="<?=$additionaLink->website()->url()?>"><?=$additionaLink->website()->name()?></a>.
							</p>
						<?php endforeach; ?>
					<?php endif; ?>

					<hr />
					<h2 id="about"><?=tl('About %s', $content->getCompany()->getTitle())?></h2>
					<p>
						<strong><?=tl('Boilerplate')?></strong><br/>
						<?=$content->getCompany()->getDescription()?>
					</p>
					<p>
						<strong><?=tl('More information')?></strong><br/>
						<?=tlHtml(
								'More information on %s, our logo & relevant media are available <a href="%s">here</a>.',
								$content->getCompany()->getTitle(),
								\Presskit\Helpers::url(
									(count($content->getAdditionalInfo()->languages) > 1
										? '?l='.$content->getAdditionalInfo()->language
										: '/'
									),
									(count($content->getAdditionalInfo()->languages) > 1
										? '/'.$content->getAdditionalInfo()->language
										: '/'
									)
								)
						)?>
					</p>

					<?php if (count($content->getCredits()) > 0 || count($content->getCompany()->getContacts()) > 0): ?>
					<hr />
					<div class="uk-grid">
						<div class="uk-width-medium-1-2">
						<?php if (count($content->getCredits()) > 0): ?>
							<h2 id="credits"><?=tl('%s Credits', $content->getTitle())?></h2>
							<?php foreach ($content->getCredits() as $credit): ?>
								<p>
									<?php if ((string) $credit->website() === ''): ?>
										<strong><?=$credit->name()?></strong><br/>
										<?=$credit->role()?>
									<?php else: ?>
										<strong><?=$credit->name()?></strong><br/>
										<a href="<?=$credit->website()->url()?>"><?=$credit->role()?></a>
									<?php endif; ?>
								</p>
							<?php endforeach; ?>
						<?php endif; ?>
						</div>

						<div class="uk-width-medium-1-2">
						<?php if (count($content->getCompany()->getContacts()) > 0): ?>
							<h2 id="contact"><?=tl('Contact')?></h2>
							<?php foreach($content->getCompany()->getContacts() as $contact): ?>
								<p>
									<strong><?=$contact->name()?></strong><br/>
									<?php if ((string) $contact->website() !== ''): ?>
										<a href="<?=$contact->website()->url()?>">
											<?php if ((string) $contact->website()->path() !== ''): ?>
												<?=$contact->website()->name()?><?=$contact->website()->path()?>
											<?php else: ?>
												<?=$contact->website()->name()?>
											<?php endif; ?>
										</a>
									<?php elseif ((string) $contact->uri() !== ''): ?>
										<a href="<?=$contact->uri()?>"><?=$contact->uri()?></a>
									<?php elseif ((string) $contact->email() !== ''): ?>
										<a href="mailto:<?=$contact->email()?>"><?=$contact->email()?></a>
									<?php endif; ?>
								</p>
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (count($content->getSocialContacts()) > 0): ?>
							<p>
								<strong><?=tl('Social:')?></strong><br/>
								<?php if ($content->getSocialContacts()[0]->name() == '@company'): ?>
								<?php $socialContacts = $content->getCompany()->getSocialContacts(); ?>
								<?php else: ?>
								<?php $socialContacts = $content->getSocialContacts(); ?>
								<?php endif; ?>
								<?php foreach($socialContacts as $contact): ?>
									<a href="<?=$contact->uri()?>"><?=$contact->name()?></a><br/>
								<?php endforeach; ?>
							</p>
						<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>

					<hr/>
					<p><a href="http://dopresskit.com/">presskit()</a> by Rami Ismail (<a href="http://www.vlambeer.com/">Vlambeer</a>)
						- also thanks to <a href="<?= \Presskit\Helpers::url(
								'?p='.\Presskit\Request::REQUEST_CREDITS_PAGE,
								'/'.\Presskit\Request::REQUEST_CREDITS_PAGE
							);?>">these fine folks</a>.</p>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.0.4/jquery.imagesloaded.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/masonry/3.1.2/masonry.pkgd.min.js"></script>
		<script type="text/javascript">
			$( document ).ready(function()
			{
				var container = $('.images');

				container.imagesLoaded( function()
				{
					container.masonry(
					{
						itemSelector: '.uk-width-medium-1-2',
					});
				});
			});
		</script>

		<?php if ($content->getAdditionalInfo()->google_analytics !== null): ?>
			<script type="text/javascript">
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

				ga('create', '<?=$content->getAdditionalInfo()->google_analytics?>', 'auto');
				ga('send', 'pageview');

			</script>
		<?php endif; ?>
	</body>
</html>
