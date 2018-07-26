<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TemplatePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */

class TemplatePage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=templates';

	/**
	 * @var string
	 */
	public static $namePage = "Template Management";

	/**
	 * @var string
	 */
	public static $nameEditPage = 'Template Management: [ Edit ]';

	/**
	 * @var array
	 */
	public static $fieldName = "#jform_name";

	/**
	 * @var array
	 */
	public static $fieldSection = ['id' => 'jform_section'];

	/**
	 * @var string
	 */
	public static $statePath = "//tr/td[6]/a";
}
