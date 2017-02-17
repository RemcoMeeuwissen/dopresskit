<?php

namespace Presskit\Content;

use Presskit\Value\Text;
use Presskit\Value\Platform;
use Presskit\Value\Price;
use Presskit\Value\URL;
use Presskit\Value\Contact;
use Presskit\Value\History;
use Presskit\Value\Trailer;
use Presskit\Value\Award;
use Presskit\Value\Quote;
use Presskit\Value\AdditionalLink;
use Presskit\Value\Credit;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
*/
class ReleaseContent
extends SharedContent
implements Content
{
    private $releaseDate = '';
	private $canRequestCopy = null;
    private $platforms = [];
    private $prices = [];
    private $features = [];
	private $additionals = [];
	private $companyContent = null;


    public function setReleaseDate($date)
    {
        $this->releaseDate = new Text($date);
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function setPressCanRequestCopy($canRequestCopy)
    {
		if (preg_match('/^(true|false|0|1|yes|no|y|n|t|f)$/i', strtolower(trim($canRequestCopy)), $tmp))
		{
			switch($tmp[1])
			{
				case 'true':
				case 't':
				case '1':
				case 'y':
				case 'yes':
				{
					$this->canRequestCopy = true;
					break;
				}
				case 'false':
				case 'f':
				case '0':
				case 'n':
				case 'no':
				{
					$this->canRequestCopy = false;
					break;
				}
			}
		}
    }

    public function canPressRequestCopy()
    {
        return $this->canRequestCopy;
    }

    public function addPlatform($name, $uri, $email = null)
    {
        $platform = new Platform($name, $uri, $email);

        if ((string) $platform !== '') {
            $this->platforms[] = $platform;
        }
    }

    public function getPlatforms()
    {
        return $this->platforms;
    }

    public function addPrice($currency, $value)
    {
        $price = new Price($currency, $value, '');

        if ((string) $price !== '') {
            $this->prices[] = $price;
        }
    }

    public function getPrices()
    {
        return $this->prices;
    }

    public function addFeature($featureText)
    {
        $feature = new Text($featureText);

        if ((string) $feature !== '') {
            $this->features[] = $feature;
        }
    }

    public function getFeatures()
    {
        return $this->features;
    }

    public function addAdditional($title, $description, $uri)
    {
        $additional = new Additional($title, $description, $uri);
        if ((string) $additional !== '') {
            $this->additionals[] = $additional;
        }
    }

    public function getAdditionals()
    {
        return $this->additionals;
    }

	public function setCompany(CompanyContent $companyContent)
	{
		$this->companyContent = $companyContent;
	}

	public function getCompany()
	{
		return $this->companyContent;
	}
}
