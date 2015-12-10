<?php

namespace Kyoushu\CommonBundle\Utils;

class StringUtils
{

    /**
     * @param string $text
     * @return string
     */
    public static function humanize($text)
    {
        return ucfirst(trim(strtolower(preg_replace(array('/([A-Z])/', '/[_\s]+/'), array('_$1', ' '), $text))));
    }

}