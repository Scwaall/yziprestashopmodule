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
 * Class Module
 *
 * @package Scwaall\YziPrestaShopModule
 */
class Module
{
    /**
     * Gets the module's repository list.
     *
     * @return array
     */
    public static function getRepositoryList()
    {
        $dir = dirname(__FILE__, 5) . '/models';
        if (!is_dir($dir)) {
            return array();
        }

        $list = array();
        foreach (scandir($dir) as $file) {
            if ($file !== '.' && $file !== '..' && $file !== 'index.php' && substr($file, -14, 10) === 'Repository') {
                $list[] = str_replace('.php', '', $file);
            }
        }

        return $list;
    }

    /**
     * Gets the module's dir.
     *
     * @return string
     */
    public static function getDir()
    {
        return dirname(__FILE__, 5);
    }

    /**
     * Gets the module's path.
     *
     * @return string
     */
    public static function getPath()
    {
        return self::getDir() . '/';
    }

    /**
     * Gets the module's URL like http(s)://my_shop_domain.com/modules/my_module_name.
     *
     * @param bool $http If true, returns domain name with protocol.
     * @param bool $entities If true, converts special chars to HTML entities.
     * @return string
     */
    public static function getUrl($http = true, $entities = false)
    {
        return \Tools::getShopDomainSsl($http, $entities) . '/modules/' . self::getPrefix();
    }

    /**
     * Gets the module's prefix.
     *
     * @param bool $toUppercase Defines whether to get the module's prefix in the uppercase format.
     * @return bool|string
     */
    public static function getPrefix($toUppercase = false)
    {
        $pathPartList = explode('/', self::getDir());
        $prefix = end($pathPartList);

        if ($toUppercase) {
            $prefix = \Tools::strtoupper($prefix);
        }

        unset($pathPartList);
        return $prefix;
    }
}
