<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_companypartners
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace NXD\Component\Companypartners\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

/**
 * Companypartners Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_companypartners
 *
 * @since  1.0.0
 */

abstract class RouteHelper{
	/**
	 * Get the URL route for partners from a partner ID, partners category ID and language
	 *
	 * @param   integer  $id        The id of the partner
	 * @param   integer  $catid     The id of the category
	 * @param   mixed    $language  The language being used
	 *
	 * @return  string             The URL route for the partner
	 *
	 * @since  1.0.0
	 */

	public static function getPartnersRoute($id, $catid, $language = 0){
		// Create the link
		$link = 'index.php?option=com_companypartners&view=partners&id=' . $id;
		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}
		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}
		return $link;
	}

	/**
	 * Get the URL route for a partner from a partner ID, Partners category ID and language
	 *
	 * @param   integer  $id        The id of the partner
	 * @param   integer  $catid     The id of the partner's category
	 * @param   mixed    $language  The language being used
	 *
	 * @return string              The URL route for the partner
	 *
	 * @since  1.0.0
	 *
	 */
	public static function getPartnerRoute($id, $catid, $language = 0){
		// Create the link
		$link = 'index.php?option=com_companypartners&view=partner&id=' . $id;
		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}
		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}
		return $link;
	}

	/**
	 * Get the URL route for a partner category from a category ID and language
	 *
	 * @param mixed $catid The id of the category
	 * @param mixed $language The language being used
	 *
	 * @return string The URL route for the category
	 *
	 * @since 1.0.0
	 *
	 */
	public static function getCategoryRoute($catid, $language = 0){
		if($catid instanceof CategoryNode){
			$id = $catid->id;
		} else {
			$id = (int) $catid;
		}
		if($id < 1){
			$link = '';
		} else {
			$link = 'index.php?option=com_companypartners&view=category&id=' . $id;
			if($language && $language !== '*' && Multilanguage::isEnabled()){
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}
}