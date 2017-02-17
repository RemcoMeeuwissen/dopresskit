<?php

namespace Presskit\Content;

use Presskit\Value\Text;
use Presskit\Value\Website;
use Presskit\Value\URI;
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
class CompanyContent
extends SharedContent
implements Content
{
    private $foundingDate = '';
    private $releaseDate = '';
    private $pressContact = '';
    private $location = '';
    private $address = [];
	private $addressUrl = '';
    private $phone = '';
    private $features = [];
    private $contacts = [];


    public function setFoundingDate($date)
    {
        $this->foundingDate = new Text($date);
    }

    public function getFoundingDate()
    {
        return $this->foundingDate;
    }

    public function setReleaseDate($date)
    {
        $this->releaseDate = new Text($date);
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function setPressContact($contact)
    {
        $this->pressContact = new Text($contact);
    }

    public function getPressContact()
    {
        return $this->pressContact;
    }

    public function setLocation($location)
    {
        $this->location = new Text($location);
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function addAddressLine($addressLineText)
    {
        $addressLine = new Text($addressLineText);

        if ((string) $addressLine !== '') {
            $this->address[] = $addressLine;
        }
    }

    public function addAddressUrl($addressUrl)
    {
        $url = new URI($addressUrl);

        if ((string) $url !== '') {
            $this->addressUrl = $url;
        }
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getAddressUrl()
    {
        return $this->addressUrl;
    }

    public function setPhone($phone)
    {
        $this->phone = new Text($phone);
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function addContact($name, $website, $email)
    {
        $contact = new Contact($name, $website, $email);

        if ((string) $contact !== '') {
            $this->contacts[] = $contact;
        }
    }

    public function getContacts()
    {
        return $this->contacts;
    }
}
