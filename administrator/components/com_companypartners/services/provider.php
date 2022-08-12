<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use NXD\Component\Companypartners\Administrator\Extension\CompanypartnersComponent;
/**
 * The Company Partners service provider.
 * https://github.com/joomla/joomla-cms/pull/20217
 *
 * @since  1.0.0
 */

return new class implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     * @param Container $container The DI container.
     * @return  void
     * @since   1.0.0
     */

    public function register(Container $container)
    {
        $container->registerServiceProvider(new CategoryFactory('\\NXD\\Component\\Companypartners'));
        $container->registerServiceProvider(new MVCFactory('\\NXD\\Component\\Companypartners'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\NXD\\Component\\Companypartners'));
        $container->set(

            ComponentInterface::class,
            function (Container $container) {
                $component = new CompanypartnersComponent($container->get(ComponentDispatcherFactoryInterface::class));
                $component->setRegistry($container->get(Registry::class));
                return $component;
            }
        );
    }
};