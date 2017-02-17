<?php

namespace Presskit\Value;

class Website
{
    private $url;
    private $name;
    private $path;

    public function __construct($value)
    {
        $value = (string) $value;

        if ($value !== '' && filter_var($value, FILTER_VALIDATE_URL) && substr($value, 0, 4) === 'http')
		{
            $this->url = $value;

            $host = parse_url($value, PHP_URL_HOST);

            if (substr($host, 0, 4) === 'www.') {
                $host = substr($host, 4);
            }

            $this->name = $host;

            $this->path = parse_url($value, PHP_URL_PATH);
        }
    }

    public function __toString()
    {
        if ($this->url === null) {
            return '';
        }

        return $this->name . ' (' . $this->url . ')';
    }

    public function url()
    {
        if ($this->url === null) {
            return '';
        }

        return $this->url;
    }

    public function name()
    {
        if ($this->url === null) {
            return '';
        }

        return $this->name;
    }

    public function path()
    {
        if ($this->path === null) {
            return '';
        }

        return $this->path;
    }
}
