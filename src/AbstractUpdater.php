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
 * Class AbstractUpdater
 *
 * @package Scwaall\YziPrestaShopModule
 */
abstract class AbstractUpdater
{
    /** @var \Module $module The module's instance. */
    private $module;

    /**
     * Updates the module.
     *
     * @return bool
     */
    abstract public function update();

    /**
     * AbstractUpdater constructor.
     *
     * @param \Module $module The module's instance.
     */
    public function __construct(\Module $module)
    {
        $this->setModule($module);
    }

    /**
     * Gets an instance of an Updater object.
     *
     * @param \Module $module The module's instance.
     * @return static
     */
    public static function getInstance(\Module $module)
    {
        return new static($module);
    }

    /**
     * Gets the module's instance.
     *
     * @return \Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Sets the module's instance.
     *
     * @param \Module $module The module's instance.
     * @return $this
     */
    private function setModule(\Module $module)
    {
        $this->module = $module;
        return $this;
    }
}
