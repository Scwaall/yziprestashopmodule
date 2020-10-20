<?php
/**
 * NOTICE OF LICENSE
 *
 * @author Scwaall
 * @copyright Copyright (c) Scwaall - 2020
 * @package YziPrestaShopModule
 * @support scwaall@gmail.com
 */

namespace Scwaall\YziPrestaShopModule\Controller;

use Context;
use Module;
use PrestaShopException;

/**
 * Class ModuleAdminController
 *
 * @package Scwaall\YziPrestaShopModule\Controller
 */
class ModuleAdminController extends \ModuleAdminController
{
    /** @var Module $module The module's instance. */
    public $module;

    /**
     * ModuleAdminController constructor.
     *
     * @throws PrestaShopException
     */
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;
        parent::__construct();
    }
}
