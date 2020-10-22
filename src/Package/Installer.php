<?php
/**
 * NOTICE OF LICENSE
 *
 * @author Scwaall
 * @copyright Copyright (c) Scwaall - 2020
 * @package YziPrestaShopModule
 * @support scwaall@gmail.com
 */

namespace Scwaall\YziPrestaShopModule\Package;

use Language;
use PrestaShopException;
use Scwaall\YziPrestaShopModule\Controller;
use Scwaall\YziPrestaShopModule\Package;
use Shop;
use Tab;
use Tools;

/**
 * Class Installer
 *
 * @package Scwaall\YziPrestaShopModule
 */
class Installer extends Package
{
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
            $tab->name[$language['id_lang']] = $this->getModule()->displayName;
        }

        $tab->class_name = Controller::getAdminName('Index', get_class($this->getModule()), $this->getModule()->author);
        $tab->module = $this->getModule()->name;
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
        foreach (Tab::getCollectionFromModule($this->getModule()->name) as $tab) {
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
            if (!$this->getModule()->registerHook($hook)) {
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
            if (!$this->getModule()->unregisterHook($hook)) {
                return false;
            }
        }

        return true;
    }
}
