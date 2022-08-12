<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Companypartners\Administrator\Extension;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use NXD\Component\Companypartners\Administrator\Service\HTML\AdministratorService;
use Psr\Container\ContainerInterface;

/**
 * Component class for com_companypartners
 *
 * @since  1.0.0
 */
class CompanypartnersComponent extends MVCComponent implements BootableExtensionInterface, CategoryServiceInterface

{
    use CategoryServiceTrait;
    use HTMLRegistryAwareTrait;

    /**
     * Booting the extension. This is the function to set up the environment of the extension like
     * registering new class loaders, etc.
     *
     * If required, some initial set up can be done from services of the container, eg.
     * registering HTML services.
     *
     * @param ContainerInterface $container The container
     *
     * @return  void
     *
     * @since   1.0.0
     */

    public function boot(ContainerInterface $container)
    {
        $this->getRegistry()->register('Companypartnersadministrator', new AdministratorService);
    }
}