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

use Language;
use PrestaShopException;
use Shop;
use Tab;
use Tools;

class Module
{
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
     * @return Module
     */
    public static function getInstance(\Module $module, array $hookList = array())
    {
        return new self($module, $hookList);
    }


    /**
     * Installs the module.
     *
     * @return bool
     * @throws PrestaShopException
     */
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return (
            $this->installHookList()
        );
    }

    /**
     * Uninstalls the module.
     *
     * @return bool
     */
    public function uninstall()
    {
        return (
            $this->uninstallHookList()
        );
    }

    /**
     * Installs the module's tab.
     *
     * @param int $parentId The parent tab's ID.
     * @return bool|int
     */
    public function installTab($parentId = 0)
    {
        $tab = new Tab();
        $tab->name = array();

        // We install the module's tab into the "Modules" PrestaShop's tab by default.
        if (!$parentId) {
            $parentId = Tab::getIdFromClassName((
                'AdminParentModules'
                . (Tools::version_compare(_PS_VERSION_, '1.7', '<') ? null : 'Sf')
            ));
        }

        foreach (Language::getLanguages(true) as $language) {
            $tab->name[$language['id_lang']] = $this->module->displayName;
        }

        $tab->class_name = "Admin{$this->getNameForControllers()}Index";
        $tab->module = $this->module->name;
        $tab->id_parent = $parentId;
        $tab->active = true;
        return $tab->add();
    }

    /**
     * Uninstalls the module's tab.
     *
     * @return bool
     */
    public function uninstallTab()
    {
        foreach (Tab::getCollectionFromModule($this->module->name) as $tab) {
            if (!$tab->delete()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Installs the module's hook list.
     *
     * @return bool
     * @throws PrestaShopException
     */
    public function installHookList()
    {
        foreach ($this->getHookList() as $hook) {
            if (!$this->module->registerHook($hook)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Uninstalls the module's hook list.
     *
     * @return bool
     */
    public function uninstallHookList()
    {
        foreach ($this->getHookList() as $hook) {
            if (!$this->module->unregisterHook($hook)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets the module's name for controllers.
     *
     * @return string
     */
    public function getNameForControllers()
    {
        $moduleClassName = str_replace($this->module->author . '_', '', get_class($this->module));
        $moduleClassNamePartList = explode('_', $moduleClassName);
        $nameForControllers = Tools::ucfirst(Tools::strtolower($this->module->author));

        if (empty($moduleClassNamePartList)) {
            $nameForControllers .= $moduleClassName;
            return $nameForControllers;
        }

        foreach ($moduleClassNamePartList as $moduleClassNamePart) {
            if ($moduleClassNamePart != $this->module->author) {
                $nameForControllers .= $moduleClassNamePart;
            }
        }

        return $nameForControllers;
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
