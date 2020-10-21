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
 * Class Controller
 *
 * @package Scwaall\YziPrestaShopModule
 */
class Controller
{
    const TYPE_ADMIN = 1;
    const TYPE_FRONT = 2;

    /**
     * Gets an admin controller's name.
     *
     * @param string $controller The controller's name.
     * @param int $type The controller's type (1 for Admin or 2 for Front).
     * @param string $moduleClass The module's class.
     * @param null|string $moduleAuthor The module's author.
     * @return string
     */
    public static function getName($controller, $type, $moduleClass, $moduleAuthor = null)
    {
        if ($type == self::TYPE_ADMIN) {
            $name = 'Admin';
        } elseif ($type == self::TYPE_FRONT) {
            $name = 'Front';
        } else {
            $name = '';
        }

        // If the module's class has a namespace, we get the first and second elements of it.
        // The both elements must be like: AuthorName\ModuleName.
        if (strpos('\\', $moduleClass) !== false) {
            $moduleClassNamePartList = explode('\\', $moduleClass);
            return $name . $moduleClassNamePartList[0] . $moduleClassNamePartList[1] . $controller;
        }

        // If the class has not namespace, we format it.
        $moduleClassName = str_replace(array($moduleAuthor, '_'), '', $moduleClass);
        $moduleClassNamePartList = explode('_', $moduleClassName);
        $name .= \Tools::ucfirst(\Tools::strtolower($moduleAuthor));

        if (empty($moduleClassNamePartList)) {
            return $name . \Tools::ucfirst(\Tools::strtolower($moduleClassName)) . $controller;
        }

        foreach ($moduleClassNamePartList as $moduleClassNamePart) {
            if ($moduleClassNamePart !== $moduleAuthor) {
                $name .= \Tools::ucfirst(\Tools::strtolower($moduleClassName));
            }
        }

        return $name . $controller;
    }

    /**
     * Gets an admin controller's name.
     *
     * @param string $controller The controller's name.
     * @param string $moduleClass The module's class.
     * @param null|string $moduleAuthor The module's author.
     * @return string
     */
    public static function getAdminName($controller, $moduleClass, $moduleAuthor = null)
    {
        return self::getName($controller, self::TYPE_ADMIN, $moduleClass, $moduleAuthor);
    }

    /**
     * Gets a front controller's name.
     *
     * @param string $controller The controller's name.
     * @param string $moduleClass The module's class.
     * @param null|string $moduleAuthor The module's author.
     * @return string
     */
    public static function getFrontName($controller, $moduleClass, $moduleAuthor = null)
    {
        return self::getName($controller, self::TYPE_FRONT, $moduleClass, $moduleAuthor);
    }
}
