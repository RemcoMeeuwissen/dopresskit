<?php

namespace Presskit\Value;

use Presskit\Value\Text;
use Presskit\Value\TrailerLocation;

class Trailer
{
	const FORMAT_MOV = 'mov';
	const FORMAT_MP4 = 'mp4';
	const FORMAT_AVI = 'avi';

    private $name;
    private $locations = [];

    public function __construct($name, $locations)
    {
        $this->name = new Text($name);

        if (is_array($locations)) {
            foreach ($locations as $format => $path) {
                $location = new TrailerLocation($format, $path);

                if ((string) $location !== '') {
                    $this->locations[] = $location;
                }
            }
        }
    }

    public function __toString()
    {
        if ((string) $this->name !== '') {
            return (string) $this->name;
        }

        return '';
    }

	static public function getFileFormats()
	{
		return array(
			self::FORMAT_MOV,
			self::FORMAT_MP4,
			self::FORMAT_AVI,
		);
	}

	public function name()
    {
        if ((string) $this->name === '') {
            return '';
        }

        return $this->name;
    }

    public function locations()
    {
        if ((string) $this->name === '') {
            return '';
        }

        return $this->locations;
    }

    public function youtube()
    {
        foreach ($this->locations as $location) {
            if ((string) $location->format() === 'youtube') {
                return $location->path();
            }
        }

        return '';
    }

    public function vimeo()
    {
        foreach ($this->locations as $location) {
            if ((string) $location->format() === 'vimeo') {
                return $location->path();
            }
        }

        return '';
    }
}
