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
 * Class Package
 *
 * @package Scwaall\YziPrestaShopModule
 */
class Package
{
    const NAME = 'YziPrestaShopModule';
    const AUTHOR = 'Scwaall';
    const VERSION = '0.0.1-dev';

    /** @var \Module $module The PrestaShop module's instance. */
    private $module;

    /** @var array $hookList The module's hook list. */
    private $hookList = array();

    /**
     * Module constructor.
     *
     * @param \Module $module The PrestaShop module's instance.
     * @param array $hookList The module's hook list.
     */
    public function __construct(\Module $module, array $hookList = array())
    {
        $this->setModule($module);
        $this->setHookList($hookList);
    }

    /**
     * Gets an instance of this object.
     *
     * @param \Module $module The PrestaShop module's instance.
     * @param array $hookList The module's hook list.
     * @return Package
     */
    public static function getInstance(\Module $module, array $hookList = array())
    {
        return new self($module, $hookList);
    }

    /**
     * Gets the PrestaShop module's instance.
     *
     * @return \Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Sets the PrestaShop module's instance.
     *
     * @param \Module $module The PrestaShop module's instance.
     * @return $this
     */
    private function setModule(\Module $module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * Get the module's hook list.
     *
     * @return array
     */
    public function getHookList()
    {
        return $this->hookList;
    }

    /**
     * Sets the module's hook list.
     *
     * @param array $hookList The module's hook list.
     * @return $this
     */
    private function setHookList(array $hookList)
    {
        $this->hookList = $hookList;
        return $this;
    }
}
