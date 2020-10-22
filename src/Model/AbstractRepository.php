<?php
/**
 * NOTICE OF LICENSE
 *
 * @author Scwaall
 * @copyright Copyright (c) Scwaall - 2020
 * @package YziPrestaShopModule
 * @support scwaall@gmail.com
 */

namespace Scwaall\YziPrestaShopModule\Model;

use ObjectModelCore as ObjectModel;

/**
 * Class AbstractRepository
 *
 * @package Scwaall\YziPrestaShopModule\Model
 */
abstract class AbstractRepository extends ObjectModel
{
    /**
     * Gets the repository's primary key.
     *
     * @return string|null
     */
    public static function getPrimaryKey()
    {
        return (isset(static::$definition['primary'])
            ? static::$definition['primary']
            : null
        );
    }

    /**
     * Gets the repository's unique key.
     *
     * @return string|null
     */
    public static function getUniqueKey()
    {
        return (isset(static::$definition['unique'])
            ? static::$definition['unique']
            : null
        );
    }
}
