<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

class plgRedshop_paymentrs_payment_dibs extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		if ($element != 'rs_payment_dibs')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		$app = JFactory::getApplication();

		include JPATH_SITE . '/plugins/redshop_payment/' . $plugin . '/' . $plugin . '/extra_info.php';
	}

	public function onNotifyPaymentrs_payment_dibs($element, $request)
	{
		if ($element != 'rs_payment_dibs')
		{
			return;
		}

		$db                = JFactory::getDbo();
		$request           = JRequest::get('request');
		JPlugin::loadLanguage('com_redshop');

		$verify_status     = $this->params->get('verify_status', '');
		$invalid_status    = $this->params->get('invalid_status', '');
		$order_id          = $request['orderid'];
		$status            = $request['status'];
		$Itemid            = $request['Itemid'];
		$values            = new stdClass;

		if ($request['status'] == 'ok' && isset($request['transact']))
		{
			$tid = $request['transact'];

			if ($this->orderPaymentNotYetUpdated($db, $order_id, $tid))
			{
				$transaction_id                    = $tid;
				$values->order_status_code         = $verify_status;
				$values->order_payment_status_code = 'Paid';
				$values->log                       = JText::_('COM_REDSHOP_ORDER_PLACED');
				$values->msg                       = JText::_('COM_REDSHOP_ORDER_PLACED');
			}
		}
		else
		{
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
			$values->log                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED.');
			$values->msg                       = JText::_('COM_REDSHOP_ORDER_NOT_PLACED');
		}

		$values->transaction_id = $tid;
		$values->order_id       = $order_id;

		return $values;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$db    = JFactory::getDbo();
		$res   = false;
		$query = "SELECT COUNT(*) `qty` FROM #__redshop_order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($tid) . "'";
		$db->setQuery($query);
		$order_payment = $db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	public function onCapture_Paymentrs_payment_dibs($element, $data)
	{
		if ($element != 'rs_payment_dibs')
		{
			return;
		}

		JLoader::load('RedshopHelperAdminOrder');

		$objOrder   = new order_functions;
		$db         = JFactory::getDbo();

		JPlugin::loadLanguage('com_redshop');

		$order_id   = $data['order_id'];
		$dibsurl    = "https://payment.architrade.com/cgi-bin/capture.cgi?";
		$orderid    = $data['order_id'];
		$key2       = $this->params->get("dibs_md5key2");
		$key1       = $this->params->get("dibs_md5key1");
		$merchantid = $this->params->get("seller_id");

		$md5key  = md5($key2 . md5($key1 . 'merchant=' . $merchantid . '&orderid=' . $data['order_id'] . '&transact=' . $data["order_transactionid"] . '&amount=' . $data['order_amount']));
		$dibsurl .= "merchant=" . urlencode($this->params->get("seller_id")) . "&amount=" . urlencode($data['order_amount']) . "&transact=" . $data["order_transactionid"] . "&orderid=" . $data['order_id'] . "&force=yes&textreply=yes&md5key=" . $md5key;

		$data    = $dibsurl;
		$ch      = curl_init($data);

		// 	Execute
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data           = curl_exec($ch);
		$data           = explode('&', $data);
		$capture_status = explode('=', $data[0]);

		if ($capture_status[1] == 'ACCEPTED')
		{
			$values->responsestatus = 'Success';
			$message = JText::_('COM_REDSHOP_TRANSACTION_APPROVED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message = JText::_('COM_REDSHOP_TRANSACTION_DECLINE');
		}

		$values->message = $message;

		return $values;
	}
}
