<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Helper;

/**
 * Class CamelCaseHelper.
 *
 * http://pt.wikipedia.org/wiki/CamelCase
 */
abstract class CamelCaseHelper
{
    /**
     * @param string $string
     * @param bool   $first_char_caps
     *
     * @return string
     */
    public static function encode($string, $first_char_caps = false)
    {
        $camelCase = preg_replace_callback('/_([a-z])/', function ($c) { return ucfirst($c[1]); }, $string);

        if ($first_char_caps) {
            $camelCase = ucfirst($camelCase);
        }

        return $camelCase;
    }

    /**
     * @param string $string
     * @param string $splitter
     *
     * @return string
     */
    public static function decode($string, $splitter = '_')
    {
        return strtolower(preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter.'$0', $string)));
    }
}
