# YziPrestaShopModule 0.0.1-dev
Package for PrestaShop modules.

Support: scwaall@gmail.com.

## Install a PrestaShop module

In your module's root file 'mymodule/mymodule.php' :

```php
class MyModule extends Module
{
    /** @var array|string[] Your module's hook list. */
    const HOOK_LIST = array();

    /**
     * Installs the module.
     *
     * @return bool
     */
    public function install()
    {
        $installer = new \Scwaall\YziPrestaShopModule\Installer($this);
        $installer->setHookList(self::HOOK_LIST);

        try {
            return (
                parent::install()
                && $installer->install()
                // Use this method if you want a tab under "Modules" PrestaShop's tab.
                // You can add a parameter with a parent's tab ID if you want to place your tab in a specific place.
                && $installer->installTab()
            );
        } catch (PrestaShopException $e) {
            $this->_errors[] = Tools::displayError($e->getMessage());
            return false;
        }
    }

    /**
     * Uninstalls the module.
     *
     * @return bool
     */
    public function uninstall()
    {
        $installer = new \Scwaall\YziPrestaShopModule\Installer($this);
        $installer->setHookList(self::HOOK_LIST);

        return (
            $installer->uninstallTab()
            && $installer->uninstall()
            && parent::uninstall()
        );
    }
}
```
