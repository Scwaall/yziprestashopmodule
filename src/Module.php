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
}
