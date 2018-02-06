<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Discount Product
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.1.0
 */
class RedshopViewDiscount_Product extends RedshopViewForm
{
	/**
	 * Method for get page title.
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public function getTitle()
	{
		return JText::_('COM_REDSHOP_DISCOUNT_PRODUCT_MANAGEMENT') . ': <small>[ ' . JText::_('COM_REDSHOP_EDIT') . ' ]</small>';
	}
}