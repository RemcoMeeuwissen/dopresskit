<?php

namespace Presskit\Value;

use Presskit\Value\Text;

class TrailerLocation
{
	const URL_YOUTUBE_LINK  = '//www.youtube.com/watch?v=%s';
	const URL_YOUTUBE_EMBED = '//www.youtube.com/embed/%s';
	const URL_VIMEO_LINK    = '//vimeo.com/%s';
	const URL_VIMEO_EMBED   = '//player.vimeo.com/video/%s';

    private $format;
    private $path;

    public function __construct($format, $path)
    {
        $this->format = new Text(strtolower($format));
        $this->path = new Text($path);
    }

    public function __toString()
    {
        if ((string) $this->format !== '' && (string) $this->path !== '')
		{
            if ((string) $this->format === 'youtube')
			{
                return sprintf(self::URL_YOUTUBE_LINK, $this->path);
            }
			else
			if ((string) $this->format === 'vimeo')
			{
                return sprintf(self::URL_VIMEO_LINK, $this->path);
            }
			else
			if (in_array((string)$this->format, Trailer::getFileFormats ()))
			{
				if (!preg_match('/\.'.(string)$this->format.'$/i', (string)$this->path))
				{
					return (string)$this->path.(string)$this->format;
				}
				else
				{
					return (string)$this->path;
				}
            }
            return $this->format . ' (' . $this->path . ')';
        }

        return '';
    }

    public function format()
    {
        if ((string) $this->format !== '' || (string) $this->path !== '') {
            return $this->format;
        }

        return '';
    }

    public function path()
    {
        if ((string) $this->format !== '' || (string) $this->path !== '') {
            return $this->path;
        }

        return '';
    }
}
