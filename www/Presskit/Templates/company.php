<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>PressKit: <?=$content->getTitle()?></title>
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

					<a class="nav-header" href="<?=$content->getWebsite()->url()?>"><?=$content->getWebsite()->name()?></a>

					<ul class="uk-nav uk-nav-side">
						<?php if (count($content->getAdditionalInfo()->languages) > 1): ?>
							<li class="language-select">
								<a>
									<?=tl('Language: ')?>
									<select onchange="document.location = '<?= \Presskit\Helpers::url('?l=', 'index-')?>' + this.value + '<?= \Presskit\Helpers::url('', '.html')?>';">
										<?php foreach($content->getAdditionalInfo()->languages as $tag => $name): ?>
											<option value="<?=$tag?>" <?php if ($tag == $content->getAdditionalInfo()->language): ?>selected="selected"<?php endif; ?>><?= htmlspecialchars($name)?></option>
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

						<?php if (count($content->getAdditionalInfo()->releases) > 0): ?>
							<li><a href="#projects"><?=tl('Projects')?></a></li>
						<?php endif; ?>

						<?php if (count($content->getTrailers()) !== 0 && !$content->hasSkipEmpty('trailers')): ?>
						<li><a href="#trailers"><?=tl('Videos')?></a></li>
						<?php endif; ?>

						<?php
							if (
									$content->getAdditionalInfo()->config->hasSkipEmpty('images.company')
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

						<?php if (count($content->getAdditionalLinks()) > 0): ?>
							<li><a href="#links"><?=tl('Additional Links')?></a></li>
						<?php endif; ?>

						<?php if (count($content->getCredits()) > 0): ?>
							<li><a href="#credits"><?=tl('Team')?></a></li>
						<?php endif; ?>

						<?php if (count($content->getContacts()) > 0): ?>
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
											: ''
										),
										(count($content->getAdditionalInfo()->languages) > 1
											? $content->getAdditionalInfo()->language
											: ''
										)
									)?>"><?=$content->getTitle()?></a><br/>
								<?=tl('Based in %s', $content->getLocation())?>
							</p>

							<?php if (!empty($content->getFoundingDate())): ?>
							<p>
								<strong><?=tl('Founding date:')?></strong><br/>
								<?=$content->getFoundingDate()?>.
							</p>
							<?php endif; ?>

							<?php if (!empty($content->getWebsite())): ?>
							<p>
								<strong><?=tl('Website:')?></strong><br/>
								<a href="<?=$content->getWebsite()->url()?>" target="_blank"><?=$content->getWebsite()->name()?></a>
							</p>
							<?php endif; ?>

							<?php if (!empty($content->getPressContact())): ?>
							<p>
								<strong><?=tl('Press / Business Contact:')?></strong><br/>
								<a href="mailto:<?=$content->getPressContact()?>"><?=$content->getPressContact()?></a>
							</p>
							<?php endif; ?>

							<?php if (count($content->getSocialContacts()) > 0): ?>
							<p>
							  <strong><?=tl('Social:')?></strong><br/>

								<?php foreach($content->getSocialContacts() as $contact): ?>
									<a href="<?=$contact->uri()?>"><?=$contact->name()?></a><br/>
								<?php endforeach; ?>
							</p>
							<?php endif; ?>

							<?php if (count($content->getAdditionalInfo()->releases) > 0): ?>
							<p>
								<strong><?=tl('Releases:')?></strong><br />

								<?php foreach ($content->getAdditionalInfo()->releases as $release): ?>
									<a href="<?=$release['url']?>"><?=$release['name']?></a><br/>
								<?php endforeach; ?>
							</p>
							<?php endif; ?>

							<?php if (count($content->getAddress()) > 0): ?>
							<p>
								<?php if ($content->getAddressUrl()): ?><a href="<?=$content->getAddressUrl()?>" target="_blank"><?php endif; ?>
								<strong><?=tl('Address:')?></strong>
								<?php if ($content->getAddressUrl()): ?></a><?php endif; ?>
								<br/>
								<?php foreach($content->getAddress() as $addressLine): ?>
									<?=$addressLine?><br/>
								<?php endforeach; ?>
							</p>
							<?php endif; ?>

							<?php if (!empty($content->getPhone())): ?>
							<p>
								<strong><?=tl('Phone:')?></strong><br/>
								<?=$content->getPhone()?>
							</p>
							<?php endif; ?>
						</div>

						<div class="uk-width-medium-4-6">
							<h2 id="description"><?=tl('Description')?></h2>
							<p><?=$content->getDescription()?></p>

							<?php if ($content->getHistory() > 0): ?>
								<h2 id="history"><?=tl('History')?></h2>
								<?php foreach ($content->getHistory() as $history): ?>
									<strong><?=$history->heading()?></strong>
									<p><?=$history->body()?></p>
								<?php endforeach; ?>
							<?php endif; ?>

							<?php if (count($content->getAdditionalInfo()->releases) > 0): ?>
							<h2 id="projects"><?=tl('Projects')?></h2>
							<ul>
								<?php foreach ($content->getAdditionalInfo()->releases as $release): ?>
									<li><a href="<?=$release['url']?>"><?=$release['name']?></a></li>
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
							$content->getAdditionalInfo()->config->hasSkipEmpty('images.company')
						 && ($content->getAdditionalInfo()->images_archive_size == 0)
						 && count($content->getAdditionalInfo()->images) == 0
					   ):
				?>
				<?php else: ?>
					<hr />
					<h2 id="images"><?=tl('Images')?></h2>
					<?php if ($content->getAdditionalInfo()->images_archive_size != 0): ?>
						<a href="<?=
							$content->getAdditionalInfo()->config->relativePath(
								$content->getAdditionalInfo()->config->imageZipFilename
							)?>"><div class="uk-alert"><?=tl('download all screenshots & photos as .zip (%s)', $content->getAdditionalInfo()->images_archive_size)?></div></a>
					<?php endif; ?>

					<?php if (count($content->getAdditionalInfo()->images) > 0): ?>
						<div class="uk-grid images">
							<?php foreach ($content->getAdditionalInfo()->images as $image): ?>
								<div class="uk-width-medium-1-2">
									<a href="<?php
										$content->getAdditionalInfo()->config->relativePath(
											$content->getAdditionalInfo()->config->imagesDirname
										)
										.$image?>"><img src="<?=
											$content->getAdditionalInfo()->config->relativePath(
												$content->getAdditionalInfo()->config->imagesDirname
											)
											.$image?>" alt="<?=$image?>" /></a>
								</div>
							<?php endforeach; ?>
						</div>
					<?php else: ?>
						<div class="uk-width-medium-1-1">
							<p class="images-text"><?=tlHtml('There are far more images available for %s, but these are the ones we felt would be most useful to you. If you have specific requests, please do <a href="#contact">contact us</a>!', $content->getTitle())?></p>
						</div>
					<?php endif; ?>
				<?php endif; ?>

					<hr />
					<h2 id="logo"><?=tl('Logo & Icon')?></h2>
					<?php if ($content->getAdditionalInfo()->logo_archive_size != 0): ?>
						<a href="<?=
							$content->getAdditionalInfo()->config->relativePath(
								$content->getAdditionalInfo()->config->imageLogoZipFilename
							)?>"><div class="uk-alert"><?=tl('download logo files as .zip (%s)', $content->getAdditionalInfo()->logo_archive_size)?></div></a>
					<?php endif; ?>
					<div class="uk-grid images">
						<?php if (!empty($content->getAdditionalInfo()->logo)): ?>
							<div class="uk-width-medium-1-2"><a href="<?=$content->getAdditionalInfo()->logo?>"><img src="<?=$content->getAdditionalInfo()->logo?>" alt="logo" /></a></div>
						<?php endif; ?>

						<?php if (!empty($content->getAdditionalInfo()->icon)): ?>
							<div class="uk-width-medium-1-2"><a href="<?=$content->getAdditionalInfo()->icon?>"><img src="<?=$content->getAdditionalInfo()->icon?>" alt="icon" /></a></div>
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
								<li><?=$award->award()?> - <cite><?=$award->description()?></cite></li>
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

					<?php if (count($content->getCredits()) > 0 || count($content->getContacts()) > 0): ?>
					<hr />
					<div class="uk-grid">
						<div class="uk-width-medium-1-2">
						<?php if (count($content->getCredits()) > 0): ?>
							<h2 id="credits"><?=tl('Team & Repeating Collaborators')?></h2>
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
						<?php if (count($content->getContacts()) > 0): ?>
							<h2 id="contact"><?=tl('Contact')?></h2>
							<?php foreach($content->getContacts() as $contact): ?>
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
