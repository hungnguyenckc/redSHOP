<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class importController extends JController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function importdata()
	{
		ob_clean();
		$model = $this->getModel('import');
		$model->importdata();
	}
}


