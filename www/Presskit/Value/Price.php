<?php

namespace Presskit\Value;

use Presskit\Value\Text;


class Price
{
    private $currency;
    private $value;
	private $valueFloat = 0.0;

    public function __construct($currency, $value)
    {
        $this->currency = new Text($currency);
		if (preg_match('/^([^0-9\.,]*)?([0-9\.,]+)([^0-9\.,]*)?$/', $value, $tmp))
		{
			$this->valueFloat = (float)str_replace(',', '.', $tmp[2]);
		}
        $this->value = $value;
    }

    public function __toString()
    {
        if ((string) $this->currency !== '') {
			return $this->currency . ' (' . $this->value . ')';
        }

        return '';
    }

    public function currency()
    {
        if ((string) $this->currency === '') {
            return '';
        }
        return $this->currency;
    }

    public function value()
    {
        if ((string) $this->currency != '') {
            return $this->value;
        }
		return '';
    }

	public function valueAsFloat()
    {
        if ((string) $this->currency != '') {
            return (float)$this->valueFloat;
        }
		return 0.0;
    }
}
