<?php
/**
 * NOTICE OF LICENSE
 *
 * @author Scwaall
 * @copyright Copyright (c) Scwaall - 2020
 * @package YziPrestaShopModule
 * @support scwaall@gmail.com
 */

namespace Scwaall\YziPrestaShopModule;

/**
 * Class Tools
 *
 * @package Scwaall\YziPrestaShopModule
 */
class Tools
{
    /**
     * Debugs a variable with an IP filtering.
     *
     * @param string $ip The IP address.
     * @param mixed $var The variable to debug.
     * @param bool $die Defines whether to die.
     */
    public static function debugIp($ip, $var, $die = true)
    {
        if ($ip === self::getIpAddress()) {
            self::debug($var, $die);
        }
    }

    /**
     * Debugs a variable.
     *
     * @param mixed $var The variable to debug.
     * @param bool $die Defines whether to die.
     */
    public static function debug($var, $die = true)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);

        echo '<pre>';

        print_r('<strong>'.$backtrace[0]['file'].' on line '.$backtrace[0]['line'].':</strong><br />');
        print_r('('.gettype($var).') ');
        print_r($var);

        echo '</pre>';

        if ($die) {
            die();
        }
    }

    /**
     * Gets the IP address.
     *
     * @return string
     */
    public static function getIpAddress()
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)
        ) {
            foreach ($matches[0] as $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])
            && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])
        ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])
            && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])
        ) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP'])
            && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])
        ) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }

        return $ip;
    }

    /**
     * Returns the JSON representation of a value.
     *
     * @param mixed $data
     * The value being encoded.
     * Can be any type except a resource.
     * All string data must be UTF-8 encoded.
     * PHP implements a superset of JSON - it will also encode and decode scalar types and NULL.
     * The JSON standard only supports these values when they are nested inside an array or an object
     * @param int $options [optional]
     * Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE.
     * JSON_THROW_ON_ERROR The behaviour of these constants is described on the JSON constants page
     * @param int $depth [optional]
     * Set the maximum depth. Must be greater than zero.
     * @return false|string
     */
    public static function jsonEncode($data, $options = 0, $depth = 512)
    {
        if (\Tools::version_compare(_PS_VERSION_, '1.7', '<')) {
            return \Tools::jsonEncode($data, $options, $depth);
        }

        return json_encode($data, $options, $depth);
    }

    /**
     * Decodes a JSON string.
     *
     * @param string $data
     * The json string being decoded.
     * This function only works with UTF-8 encoded strings.
     * PHP implements a superset of JSON - it will also encode and decode scalar types and NULL.
     * The JSON standard only supports these values when they are nested inside an array or an object.
     * @param bool $assoc [optional]
     * When TRUE, returned objects will be converted into associative arrays.
     * @param int $depth [optional]
     * User specified recursion depth.
     * @param int $options
     * @return mixed
     */
    public static function jsonDecode($data, $assoc = false, $depth = 512, $options = 0)
    {
        if (\Tools::version_compare(_PS_VERSION_, '1.7', '<')) {
            return \Tools::jsonDecode($data, $assoc, $depth, $options);
        }

        return json_decode($data, $assoc, $depth, $options);
    }
}
