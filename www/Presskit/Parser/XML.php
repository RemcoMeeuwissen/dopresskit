<?php

namespace Presskit\Parser;

use Presskit\Content;
use SimpleXMLElement;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class XML
{
    private $content;
    private $data;

    public function __construct($file, /*Content*/ $content)
    {
        $this->content = $content;
        $this->data = new SimpleXMLElement(file_get_contents($file));
    }

    public function parse()
    {
		if ($this->content instanceof \Presskit\Content\CompanyContent)
		{
			return $this->parseCompany();
		}
		else
		if ($this->content instanceof \Presskit\Content\ReleaseContent)
		{
			return $this->parseRelease();
		}
		else
		{
			throw new \Exception('Unimplemented Content!');
		}
	}

	private function parseShared()
	{
		$this->findTitle();
		$this->findWebsite();
		$this->findDescription();
        $this->findTrailers();
        $this->findAwards();
        $this->findQuotes();
        $this->findAdditionalLinks();
        $this->findCredits();
		$this->findMonetization();
		$this->findSocialContacts();
	}

	private function parseCompany()
	{
		$this->parseShared();

        $this->findFoundingDate();
        $this->findPressContact();
        $this->findLocation(); // tag: based-in
        $this->findAddress();
        $this->findPhone();
        $this->findCompanyHistory();
        $this->findContacts();

        return $this->content;
    }

    public function parseRelease()
    {
		$this->parseShared();

        $this->findReleaseDate();
		$this->findPressCanRequestCopy();
		$this->findPlatforms();
		$this->findPrices();
		$this->findReleaseHistory();
		$this->findFeatures();

        return $this->content;
    }

    private function findTitle()
    {
        if (count($this->data->title) > 0) {
            $this->content->setTitle($this->data->title);
        }
    }

    private function findFoundingDate()
    {
        if (count($this->data->{'founding-date'}) > 0) {
            $this->content->setFoundingDate($this->data->{'founding-date'});
        }
    }

    private function findReleaseDate()
    {
        if (count($this->data->{'release-date'}) > 0) {
            $this->content->setReleaseDate($this->data->{'release-date'});
        }
    }

    private function findWebsite()
    {
        if (count($this->data->website) > 0) {
            $this->content->setWebsite($this->data->website);
        }
    }

    private function findPressContact()
    {
        if (count($this->data->{'press-contact'}) > 0) {
            $this->content->setPressContact($this->data->{'press-contact'});
        }
    }

    private function findPressCanRequestCopy()
    {
        if (count($this->data->{'press-can-request-copy'}) > 0) {
            $this->content->setPressCanRequestCopy($this->data->{'press-can-request-copy'});
        }
    }

    private function findLocation()
    {
        if (count($this->data->{'based-in'}) > 0) {
            $this->content->setLocation($this->data->{'based-in'});
        }
    }

    private function findSocialContacts()
    {
        if (count($this->data->socials) > 0)
		{
			if ((string)$this->data->socials == '@company')
			{
				$this->content->addSocialContact('@company', 'http://@company');
			}
			else
			{
				foreach ($this->data->socials->social as $social)
				{
					$name = $social->name;
					foreach($social->name->attributes() as $attr => $value)
					{
						if (preg_match('/^htmlSpecialChars/i', $attr) && $this->getBoolean(trim($value)))
						{
							$name = htmlspecialchars_decode($name);
							break;
						}
					}
					$this->content->addSocialContact($name, $social->link);
				}
			}
        }
    }

    private function findAddress()
    {
        if (count($this->data->address) > 0)
		{
			foreach($this->data->address[0]->attributes() as $attr => $value)
			{
				if (preg_match('/^url$/i', $attr) && trim($value) != '')
				{
					$this->content->addAddressUrl(trim($value));
					break;
				}
			}
            foreach ($this->data->address->line as $addressLine)
			{
                $this->content->addAddressLine($addressLine);
            }
        }
    }

    private function findPhone()
    {
        if (count($this->data->phone) > 0) {
            $this->content->setPhone($this->data->phone);
        }
    }

    private function findDescription()
    {
        if (count($this->data->description) > 0)
		{
			$tagText = $this->data->description;
			foreach($tagText->attributes() as $attr => $value)
			{
				if (preg_match('/^htmlSpecialChars/i', $attr) && $this->getBoolean(trim($value)))
				{
					$tagText = htmlspecialchars_decode($tagText);
					break;
				}
			}
            $this->content->setDescription($tagText);
        }
    }

	private function findCompanyHistory()
	{
		if (count($this->data->histories) > 0)
		{
			foreach ($this->data->histories->history as $history)
			{
				$tagText = $history->text;
				foreach($tagText->attributes() as $attr => $value)
				{
					if (preg_match('/^htmlSpecialChars/i', $attr) && $this->getBoolean(trim($value)))
					{
						$tagText = htmlspecialchars_decode($tagText);
						break;
					}
				}
				$this->content->addHistory($history->header, trim($tagText));
			}
		}
	}

    private function findReleaseHistory()
    {
        if (count($this->data->history) > 0) {
            $this->content->addHistory('unused', $this->data->history);
        }
		else
		if (count($this->data->histories) > 0)
		{
			foreach ($this->data->histories->history as $history)
			{
				$tagText = $history->text;
				foreach($tagText->attributes() as $attr => $value)
				{
					if (preg_match('/^htmlSpecialChars/i', $attr) && $this->getBoolean(trim($value)))
					{
						$tagText = htmlspecialchars_decode($tagText);
						break;
					}
				}
				$this->content->addHistory($history->header, trim($tagText));
			}
		}
    }

    private function findFeatures()
    {
        if (count($this->data->features) > 0) {
            foreach ($this->data->features->feature as $feature) {
                $this->content->addFeature($feature);
            }
        }
    }

    private function findPlatforms()
    {
        if (count($this->data->platforms) > 0) {
            foreach ($this->data->platforms->platform as $platform) {
                $this->content->addPlatform($platform->name, $platform->link, $platform->email);
            }
        }
    }

    private function findPrices()
    {
        if (count($this->data->prices) > 0) {
            foreach ($this->data->prices->price as $price) {
                $this->content->addPrice($price->currency, $price->value);
            }
        }
    }

    private function findTrailers()
    {
        if (count($this->data->trailers) > 0)
		{
			foreach($this->data->trailers[0]->attributes() as $attr => $value)
			{
				if (preg_match('/^skipEmpty/i', $attr) && trim($value) != '')
				{
					$this->content->setSkipEmpty('trailers', trim($value));
					break;
				}
			}
            foreach ($this->data->trailers->trailer as $trailer)
			{
                $locations = [];

                if (count($trailer->youtube) > 0) {
                    $locations['youtube'] = $trailer->youtube;
                }

                if (count($trailer->vimeo) > 0) {
                    $locations['vimeo'] = $trailer->vimeo;
                }

                if (count($trailer->mov) > 0) {
                    $locations['mov'] = $trailer->mov;
                }

                if (count($trailer->mp4) > 0) {
                    $locations['mp4'] = $trailer->mp4;
                }

                $this->content->addTrailer($trailer->name, $locations);
            }
        }
    }

    private function findAwards()
    {
        if (count($this->data->awards) > 0) {
            foreach ($this->data->awards->award as $award) {
                $this->content->addAward($award->description, $award->info);
            }
        }
    }

    private function findQuotes()
    {
        if (count($this->data->quotes) > 0) {
            foreach ($this->data->quotes->quote as $quote) {
                $this->content->addQuote($quote->description, $quote->name, $quote->link, $quote->website);
            }
        }
    }

    private function findAdditionalLinks()
    {
        if (count($this->data->additionals) > 0) {
            foreach ($this->data->additionals->additional as $additional) {
                $this->content->addAdditionalLink($additional->title, $additional->description, $additional->link);
            }
        }
    }

    private function findCredits()
    {
        if (count($this->data->credits) > 0) {
            foreach ($this->data->credits->credit as $credit) {
                $this->content->addCredit($credit->person, $credit->role, $credit->website);
            }
        }
    }

    private function findContacts()
    {
        if (count($this->data->contacts) > 0) {
            foreach ($this->data->contacts->contact as $contact) {
                $this->content->addContact($contact->name, $contact->link, $contact->mail);
            }
        }
    }

	private function findMonetization()
	{
		if (count($this->data->{'monetization-permission'}) > 0) {
            $this->content->setMonetization($this->data->{'monetization-permission'});
        }
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
}
