<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Update class
 *
 * @package     Redshob.Update
 *
 * @since       2.0.3
 */
class RedshopUpdate203 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/component/admin/controllers/supplier_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/controllers/tax.php',
			JPATH_ADMINISTRATOR . '/component/admin/controllers/tax_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/controllers/mass_discount_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/models/supplier_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/models/mass_discount_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/models/tax.php',
			JPATH_ADMINISTRATOR . '/component/admin/models/tax_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/tables/supplier_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/tables/mass_discount_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/tables/tax_detail.php',
			JPATH_ADMINISTRATOR . '/component/admin/views/supplier/tmpl/default.php',
			JPATH_ADMINISTRATOR . '/component/admin/views/mass_discount/tmpl/default.php',
			JPATH_SITE . '/media/com_redshop/css/media.css',
			JPATH_SITE . '/media/com_redshop/css/media-uncompressed.css',
			JPATH_SITE . '/media/com_redshop/js/media.js',
			JPATH_SITE . '/media/com_redshop/js/media-uncompressed.js',
			JPATH_ADMINISTRATOR . '/component/admin/views/order_detail/view.tcpdf.php',
			JPATH_LIBRARIES . '/redshop/helper/tcpdf.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/component/admin/views/supplier_detail',
			JPATH_ADMINISTRATOR . '/component/admin/views/tax',
			JPATH_ADMINISTRATOR . '/component/admin/views/mass_discount_detail',
			JPATH_ADMINISTRATOR . '/component/admin/views/tax_detail'
		);
	}

	/**
	 * Method for update override template when update.
	 *
	 * @return  void
	 *
	 * @since   2.0.4
	 */
	public static function updateOverrideTemplate()
	{
		$dir                  = JPATH_SITE . "/templates/";
		$codeDir              = JPATH_SITE . "/code/";
		$files                = JFolder::folders($dir);
		$templates            = array();
		$adminHelpers         = array();
		$adminTemplateHelpers = array();

		if (JFolder::exists($codeDir))
		{
			$codeFiles = JFolder::folders($codeDir);

			foreach ($codeFiles as $key => $value)
			{
				if (JFolder::exists($codeDir . 'administrator/components'))
				{
					$templates[$codeDir . 'administrator/components'] = JFolder::folders($codeDir . 'administrator/components');
				}

				if (JFolder::exists($codeDir . 'administrator'))
				{
					$templates[$codeDir . 'administrator'] = JFolder::folders($codeDir . 'administrator');
				}

				if (JFolder::exists($codeDir . 'components'))
				{
					$templates[$codeDir . 'components'] = JFolder::folders($codeDir . 'components');
				}

				if (JFolder::exists($codeDir))
				{
					$templates[$codeDir] = JFolder::folders($codeDir);
				}

				if (JFolder::exists($codeDir . 'com_redshop/helpers'))
				{
					$adminHelpers[$codeDir . 'com_redshop/helpers'] = JFolder::files($codeDir . 'com_redshop/helpers');
				}
			}
		}

		foreach ($files as $key => $value)
		{
			if (!JFile::exists($dir . $value))
			{
				$templates[$dir . $value] = JFolder::folders($dir . $value);
			}
		}

		$override   = array();
		$jsOverride = array();

		foreach ($templates as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!JFile::exists($key . '/' . $name))
				{
					if (JFolder::exists($key . '/com_redshop'))
					{
						$override[$key . '/com_redshop'] = JFolder::folders($key . '/com_redshop');
					}

					if (JFolder::exists($key . '/html'))
					{
						$override[$key . '/html'] = JFolder::folders($key . '/html');
					}

					if (JFolder::exists($key . '/js/com_redshop'))
					{
						$jsOverride[$key . '/js/com_redshop'] = JFolder::files($key . '/js/com_redshop');
					}

					if (JFolder::exists($key . '/code/com_redshop'))
					{
						$override[$key . '/code/com_redshop'] = JFolder::folders($key . '/code/com_redshop');
					}

					if (JFolder::exists($key . '/code/components/com_redshop'))
					{
						$override[$key . '/code/components/com_redshop'] = JFolder::folders($key . '/code/components/com_redshop');
					}

					if (JFolder::exists($key . '/code/com_redshop/helpers'))
					{
						$adminTemplateHelpers[$key] = JFolder::files($key . '/code/com_redshop/helpers');
					}
				}
			}
		}

		$overrideFolders       = array();
		$overrideLayoutFolders = array();
		$overrideLayoutFiles   = array();

		foreach ($override as $key => $value)
		{
			foreach ($value as $name)
			{
				if ($name == 'layouts')
				{
					$overrideLayoutFolders[$key . '/' . $name] = JFolder::folders($key . '/' . $name);
				}
				elseif (!JFile::exists($key . '/' . $name) && $name != 'layouts' && $name == 'com_redshop' || strpos($name, 'mod_redshop') !== false)
				{
					// Read all files and folders in parent folder
					$overrideFolders[$key . '/' . $name] = array_diff(scandir($key . '/' . $name), array('.', '..'));
				}
			}
		}

		$overrideFiles = array();

		foreach ($overrideFolders as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!JFile::exists($key . '/' . $name))
				{
					$overrideFiles[$key . '/' . $name] = JFolder::files($key . '/' . $name);
				}
				else
				{
					$overrideFiles[$key] = JFolder::files($key);
				}
			}
		}

		foreach ($overrideLayoutFolders as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!JFile::exists($key . '/' . $name) && $name == 'com_redshop')
				{
					$overrideLayoutFiles[$key . '/' . $name] = JFolder::files($key . '/' . $name);
				}
			}
		}

		if (!empty($overrideLayoutFiles))
		{
			foreach ($overrideLayoutFiles as $key => $value)
			{
				foreach ($value as $name)
				{
					if (!JFile::exists($key . '/' . $name))
					{
						$overrideFiles[$key . '/' . $name] = JFolder::files($key . '/' . $name);
					}
				}
			}
		}

		$replaceString = array(
			'new quotationHelper()'                                                        => 'quotationHelper::getInstance()',
			'new order_functions()'                                                        => 'order_functions::getInstance()',
			'new Redconfiguration()'                                                       => 'Redconfiguration::getInstance()',
			'new Redconfiguration'                                                         => 'Redconfiguration::getInstance()',
			'new Redtemplate()'                                                            => 'Redtemplate::getInstance()',
			'new Redtemplate'                                                              => 'Redtemplate::getInstance()',
			'new extra_field()'                                                            => 'extra_field::getInstance()',
			'new rsstockroomhelper()'                                                      => 'rsstockroomhelper::getInstance()',
			'new rsstockroomhelper'                                                        => 'rsstockroomhelper::getInstance()',
			'new shipping()'                                                               => 'shipping::getInstance()',
			'new CurrencyHelper()'                                                         => 'CurrencyHelper::getInstance()',
			'new economic()'                                                               => 'economic::getInstance()',
			'new rsUserhelper()'                                                           => 'rsUserHelper::getInstance()',
			'new rsUserhelper'                                                             => 'rsUserHelper::getInstance()',
			'GoogleAnalytics'                                                              => 'RedshopHelperGoogleanalytics',
			'new quotationHelper'                                                          => 'quotationHelper::getInstance()',
			'new order_functions'                                                          => 'order_functions::getInstance()',
			'new extra_field'                                                              => 'extra_field::getInstance()',
			'new shipping'                                                                 => 'shipping::getInstance()',
			'new CurrencyHelper'                                                           => 'CurrencyHelper::getInstance()',
			'new economic'                                                                 => 'economic::getInstance()',
			'RedshopConfig::scriptDeclaration();'                                          => '',
			'$redConfiguration'                                                            => '$Redconfiguration',
			'require_once JPATH_SITE . \'/components/com_redshop/helpers/redshop.js.php\'' => '',
		);

		$data = Redshop::getConfig()->toArray();
		$temp = JFactory::getApplication()->getUserState('com_redshop.config.global.data');

		if (!empty($temp))
		{
			$data = array_merge($data, $temp);
		}

		$data['BACKWARD_COMPATIBLE_PHP'] = 0;
		$data['BACKWARD_COMPATIBLE_JS']  = 0;
		$config                          = Redshop::getConfig();

		if (!empty($overrideFiles))
		{
			foreach ($overrideFiles as $path => $files)
			{
				foreach ($files as $file)
				{
					$content = file_get_contents($path . '/' . $file);

					foreach ($replaceString as $old => $new)
					{
						if (strstr($content, $old))
						{
							$content = str_replace($old, $new, $content);
							JFile::write($path . '/' . $file, $content);
						}
					}
				}
			}

			// Check site used MVC && Templates Override
			$data['BACKWARD_COMPATIBLE_PHP'] = 1;
		}

		if (!empty($jsOverride))
		{
			// Check site used JS Override
			$data['BACKWARD_COMPATIBLE_JS'] = 1;
		}

		JFactory::getApplication()->setUserState('com_redshop.config.global.data', $data);
		$config->save(new Registry($data));

		$replaceAdminHelper = array(
			'adminorder.php'         => 'order_functions.php',
			'admincategory.php'      => 'product_category.php',
			'adminquotation.php'     => 'quotationhelper.php',
			'adminaccess_level.php'  => 'redaccesslevel.php',
			'adminconfiguration.php' => 'redconfiguration.php',
			'adminmedia.php'         => 'redmediahelper.php',
			'adminimages.php'        => 'redshophelperimages.php',
			'adminmail.php'          => 'redshopmail.php',
			'adminupdate.php'        => 'redshopupdate.php',
			'admintemplate.php'      => 'redtemplate.php',
			'adminstockroom.php'     => 'rsstockroom.php',
			'adminshopper.php'       => 'shoppergroup.php'
		);

		$replaceSiteHelper = array(
			'currency.php'         => 'currencyhelper.php',
			'extra_field.php'      => 'extrafield.php',
			'google_analytics.php' => 'googleanalytics.php',
			'product.php'          => 'producthelper.php',
			'helper.php'           => 'redhelper.php',
			'cart.php'             => 'rscarthelper.php',
			'user.php'             => 'rsuserhelper.php'
		);

		if (!empty($adminHelpers))
		{
			foreach ($adminHelpers as $path => $files)
			{
				foreach ($replaceAdminHelper as $old => $new)
				{
					if (JFile::exists($path . '/' . $old))
					{
						if (!JFolder::exists($codeDir . 'administrator/components/com_redshop/helpers'))
						{
							JFolder::create($codeDir . 'administrator/components/com_redshop/helpers');
						}

						$src  = $codeDir . 'com_redshop/helpers/' . $old;
						$dest = $codeDir . 'administrator/components/com_redshop/helpers/' . $new;
						JFile::move($src, $dest);
					}
				}

				foreach ($replaceSiteHelper as $old => $new)
				{
					if (JFile::exists($path . '/' . $old))
					{
						if (!JFolder::exists($codeDir . 'components/com_redshop/helpers'))
						{
							JFolder::create($codeDir . 'components/com_redshop/helpers');
						}

						$src  = $codeDir . 'com_redshop/helpers/' . $old;
						$dest = $codeDir . 'components/com_redshop/helpers/' . $new;
						JFile::move($src, $dest);
					}
				}
			}
		}

		if (!empty($adminTemplateHelpers))
		{
			foreach ($adminTemplateHelpers as $path => $files)
			{
				foreach ($replaceAdminHelper as $old => $new)
				{
					if (JFile::exists($path . '/code/com_redshop/helpers/' . $old))
					{
						if (!JFolder::exists($path . '/code/administrator/components/com_redshop/helpers'))
						{
							JFolder::create($path . '/code/administrator/components/com_redshop/helpers');
						}

						$src  = $path . '/code/com_redshop/helpers/' . $old;
						$dest = $path . '/code/administrator/components/com_redshop/helpers/' . $new;
						JFile::move($src, $dest);
					}
				}

				foreach ($replaceSiteHelper as $old => $new)
				{
					if (JFile::exists($path . '/code/com_redshop/helpers/' . $old))
					{
						if (!JFolder::exists($path . '/code/components/com_redshop/helpers'))
						{
							JFolder::create($path . '/code/components/com_redshop/helpers');
						}

						$src  = $path . '/code/com_redshop/helpers/' . $old;
						$dest = $path . '/code/components/com_redshop/helpers/' . $new;
						JFile::move($src, $dest);
					}
				}
			}
		}
	}
}