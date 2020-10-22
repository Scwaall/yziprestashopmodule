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
 * Class TraitEntity
 *
 * @package Scwaall\YziPrestaShopModule\Model
 */
trait TraitEntity
{
    /**
     * Gets the entity's ID.
     *
     * @return int
     */
    public function getId()
    {
        return property_exists($this, 'id') ? (int)$this->id : 0;
    }

    /**
     * Sets the entity's ID.
     *
     * @param int $id The entity's ID.
     * @return $this
     */
    public function setId($id)
    {
        if (property_exists($this, 'id')) {
            $this->id = (int)$id;
        }

        return $this;
    }

    /**
     * Gets the date when the entity was created.
     *
     * @return string|null
     */
    public function getDateAdd()
    {
        return property_exists($this, 'date_add') ? pSQL($this->date_add) : null;
    }

    /**
     * Sets the date when the entity was created.
     *
     * @param string $dateAdd The date when the entity was created.
     * @return $this
     */
    public function setDateAdd($dateAdd)
    {
        if (property_exists($this, 'date_add')) {
            $this->date_add = pSQL($dateAdd);
        }

        return $this;
    }

    /**
     * Gets the date when the entity was updated.
     *
     * @return string|null
     */
    public function getDateUpd()
    {
        return property_exists($this, 'date_upd') ? pSQL($this->date_upd) : null;
    }

    /**
     * Sets the date when the entity was updated.
     *
     * @param string $dateUpd The date when the entity was updated.
     * @return $this
     */
    public function setDateUpd($dateUpd)
    {
        if (property_exists($this, 'date_upd')) {
            $this->date_upd = pSQL($dateUpd);
        }

        return $this;
    }
}
