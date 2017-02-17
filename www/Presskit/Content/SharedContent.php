<?php

namespace Presskit\Content;

use Presskit\Value\Text;
use Presskit\Value\Website;
use Presskit\Value\History;
use Presskit\Value\Trailer;
use Presskit\Value\Award;
use Presskit\Value\Quote;
use Presskit\Value\AdditionalLink;
use Presskit\Value\Credit;
use Presskit\Value\Contact;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
*/
abstract class SharedContent
implements Content
{
	const MONETIZATION_ASK            = 'ask';
	const MONETIZATION_NON_COMMERCIAL = 'non-commercial';
	const MONETIZATION_MONETIZE       = 'monetize';
	const MONETIZATION_FALSE          = 'false';
	const MONETIZATION_SHARED_COMPANY = '@company';

    private $title = '';
	private $website = '';
	private $description = '';
	private $trailers = [];
    private $awards = [];
    private $quotes = [];
    private $additionalLinks = [];
    private $credits = [];
    private $history = [];
	private $additionalInfo = [];
	private $hasMonetization = null;
	private $monetization = null;
	private $skipEmpty = [];
	private $socialContacts = [];


	public function setSkipEmpty($key, $value)
	{
		$this->skipEmpty[$key] = $this->getBoolean($value);
	}

	public function hasSkipEmpty($key)
	{
		return isset($this->skipEmpty[$key]);
	}

    public function setTitle($title)
    {
        $this->title = new Text($title);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setWebsite($website)
    {
        $this->website = new Website($website);
    }

    public function getWebsite()
    {
        if ($this->website === null) {
            return false;
        }

        return $this->website;
    }

    public function setDescription($description)
    {
        $this->description = new Text($description);
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function addHistory($heading, $body)
    {
        $history = new History($heading, $body);

        if ((string) $history !== '') {
            $this->history[] = $history;
        }
    }

    public function getHistory()
    {
        return $this->history;
    }

    public function addTrailer($name, $locations)
    {
        $trailer = new Trailer($name, $locations);

        if ((string) $trailer !== '') {
            $this->trailers[] = $trailer;
        }
    }

    public function getTrailers()
    {
        return $this->trailers;
    }

    public function addAward($award, $description)
    {
        $award = new Award($award, $description);

        if ((string) $award !== '') {
            $this->awards[] = $award;
        }
    }

    public function getAwards()
    {
        return $this->awards;
    }

    public function addQuote($description, $name, $website, $websiteName)
    {
        $quote = new Quote($description, $name, $website, $websiteName);

        if ((string) $quote !== '') {
            $this->quotes[] = $quote;
        }
    }

    public function getQuotes()
    {
        return $this->quotes;
    }

    public function addAdditionalLink($title, $description, $website)
    {
        $additionalLink = new AdditionalLink($title, $description, $website);

        if ((string) $additionalLink !== '') {
            $this->additionalLinks[] = $additionalLink;
        }
    }

    public function getAdditionalLinks()
    {
        return $this->additionalLinks;
    }

    public function addCredit($name, $role, $website)
    {
        $credit = new Credit($name, $role, $website);

        if ((string) $credit !== '') {
            $this->credits[] = $credit;
        }
    }

    public function getCredits()
    {
        return $this->credits;
    }

	public function setAdditionalInfo($additionalInfo)
	{
		$this->additionalInfo = $additionalInfo;
	}

	public function getAdditionalInfo()
	{
		$obj = (Object)$this->additionalInfo;
		return $obj;
	}

	public function setMonetization($monetization)
	{
		$monetization = strtolower(trim($monetization));
		switch($monetization)
		{
			case self::MONETIZATION_ASK:
			case self::MONETIZATION_NON_COMMERCIAL:
			case self::MONETIZATION_MONETIZE:
			{
				$this->hasMonetization = true;
				$this->monetization = $monetization;
				break;
			}
			case self::MONETIZATION_SHARED_COMPANY:
			{
				$this->hasMonetization = null;
				$this->monetization = $monetization;
				break;
			}
			case self::MONETIZATION_FALSE:
			default:
			{
				$this->hasMonetization = false;
				$this->monetization = $monetization;
				break;
			}
		}
	}

	public function getMonetization()
	{
		return $this->monetization;
	}

	public function hasMonetization()
	{
		return $this->hasMonetization;
	}

	private function getBoolean($value)
	{
		if (
			   preg_match('/^(yes|true|t|1)/i', (string)$value)
			|| $value === true
		   )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

    public function addSocialContact($name, $uri)
    {
        $contact = new Contact($name, $uri, '');

        if ((string) $contact !== '') {
            $this->socialContacts[] = $contact;
        }
    }

    public function getSocialContacts()
    {
        return $this->socialContacts;
    }
}
