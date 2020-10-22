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

/**
 * Class AbstractEntity
 *
 * @package Scwaall\YziPrestaShopModule\Model
 */
abstract class AbstractEntity
{
    use TraitEntity;

    /** @var string $date_add The date when the entity was created. */
    public $date_add;

    /** @var string $date_upd The date when the entity was updated. */
    public $date_upd;
}
