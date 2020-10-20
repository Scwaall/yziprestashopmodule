# YziPrestaShopModule
Package for PrestaShop modules.

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
        $installer = new \Scwaall\YziPrestaShopModule\Module($this, self::HOOK_LIST);

        return (
            parent::install()
            && $installer->install()
            // Use this method if you want a tab under "Modules" PrestaShop's tab.
            // You can add a parameter with a parent's tab ID if you want to place your tab in a specific place.
            && $installer->installTab()
        );
    }

    /**
     * Uninstalls the module.
     *
     * @return bool
     */
    public function uninstall()
    {
        $installer = new \Scwaall\YziPrestaShopModule\Module($this, self::HOOK_LIST);

        return (
            $installer->uninstallTab()
            && $installer->uninstall()
            && parent::uninstall()
        );
    }
}
```
