<?php

namespace Presskit\Value;

use Presskit\Value\Text;
use Presskit\Value\URI;
use Presskit\Value\Website;


class Platform
{
    private $name;
    private $uri;
    private $website;
	private $email;

    public function __construct($name, $uri, $email = null)
    {
        $this->name = new Text($name);
        $this->uri = new URI($uri);
        $this->website = new Website($uri);
		$this->email = new Email($email);
    }

    public function __toString()
    {
        if ((string) $this->name !== '')
		{
            if ((string) $this->website !== '')
			{
                return $this->name . ' (' . $this->website->url() . ')';
            }
			else
			if ((string) $this->uri !== '')
			{
                return $this->name . ' (' . $this->uri . ')';
            }
			else
			if ((string) $this->email !== '')
			{
				return $this->name . ' <' . $this->email . '>';
			}
			else
			{
				return (string)$this->name;
			}
        }
        return '';
    }

    public function name()
    {
        if ((string) $this->name === '')
		{
            return '';
        }
        return $this->name;
    }

    public function url()
    {
        if ((string) $this->name === '' || (string) $this->uri === '') {
            return '';
        }
		return $this->uri;
    }

    public function email()
    {
        if ((string) $this->email === '')
		{
            return '';
        }
        return $this->email;
    }

    public function website()
    {
        if ((string) $this->name === '' || (string) $this->uri === '')
		{
            return '';
        }
        return $this->website;
    }
}
