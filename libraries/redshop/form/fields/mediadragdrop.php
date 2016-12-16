<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/**
 * Renders a Media Drag and Drop
 *
 * @package  Joomla
 *
 * @since    __DEPLOY_VERSION__
 */
class JFormFieldMediadragdrop extends JFormField
{
	/**
	 * The form field type
	 *
	 * @var    string
	 *
	 * @since  __DEPLOY_VERSION
	 */
	protected $type = 'mediadragdrop';

	/**
	 * [getInput description]
	 *
	 * @return  [type]  [description]
	 */
	protected function getInput()
	{
		// Define data to display in html
		$displayData = [
			'id'           => $this->id,
			'name'         => $this->name,
			'value'        => $this->value
		];

		// Render html in layouts/html
		return RedshopLayoutHelper::render(
			'html.dropzone',
			$displayData
		);
	}
}