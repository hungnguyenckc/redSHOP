<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

$url = JURI::base();
$user = JFactory::getUser();
global $mainframe;
JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'order.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'shipping.php';
require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php';
require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'cart.php';
require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php';

$carthelper = new rsCarthelper;
$producthelper = new producthelper;
$order_functions = new order_functions;
$redhelper = new redhelper;
$redTemplate = new Redtemplate;
$shippinghelper = new shipping;
$session = JFactory::getSession();
$document = JFactory::getDocument();

// get redshop helper
$Itemid = $redhelper->getCheckoutItemid();
$option = JRequest::getVar('option', 'com_redshop');
$model = $this->getModel('checkout');
$cart = $session->get('cart');

JHTML::Script('credit_card.js', 'components/com_redshop/assets/js/', false);

$billingaddresses = $model->billingaddresses();

if (!count($billingaddresses))
{
	$msg = JText::_('COM_REDSHOP_LOGIN_USER_IS_NOT_REDSHOP_USER');
	$mainframe->Redirect("index.php?option=" . $option . "&view=account_billto&return=checkout&Itemid=" . $Itemid, $msg);
}
$paymentmethod = $redhelper->getPlugins('redshop_payment');
$selpayment_method_id = 0;

if (count($paymentmethod) > 0)
{
	$selpayment_method_id = $paymentmethod[0]->element;
}
$shippingBoxes = $shippinghelper->getShippingBox();
$selshipping_box_post_id = 0;

if (count($shippingBoxes) > 0)
{
	$selshipping_box_post_id = $shippingBoxes[0]->shipping_box_id;
}
$users_info_id = JRequest::getInt('users_info_id', $this->users_info_id);
$payment_method_id = JRequest::getCmd('payment_method_id', $selpayment_method_id);
$shipping_box_post_id = JRequest::getInt('shipping_box_id', $selshipping_box_post_id);
$shipping_rate_id = JRequest::getVar('shipping_rate_id', 0);

if ($users_info_id == 0)
{
	$users_info_id = $billingaddresses->users_info_id;
}

$onestep_template_desc = "";
$onesteptemplate = $redTemplate->getTemplate("onestep_checkout");

if (count($onesteptemplate) > 0 && $onesteptemplate[0]->template_desc)
{
	$onestep_template_desc = "<div id='divOnestepCheckout'>" . $onesteptemplate[0]->template_desc . "</div>";
}
else
{
	$onestep_template_desc = JText::_("COM_REDSHOP_TEMPLATE_NOT_EXISTS"); //"<div id=\"produkt\">\r\n<div class=\"produkt_spacer\"></div>\r\n<div class=\"produkt_anmeldelser_opsummering\">{product_rating_summary}</div>\r\n<div id=\"opsummering_wrapper\">\r\n<div id=\"opsummering_skubber\"></div>\r\n<div id=\"opsummering_link\">Clik <a href=\"ratings\" target=\"self\">her</a> for go to the review (s)</div>\r\n</div>\r\n<div id=\"produkt_kasse\">\r\n<div class=\"produkt_kasse_venstre\">\r\n<div class=\"produkt_kasse_billed\">{product_thumb_image}</div>\r\n<div class=\"produkt_kasse_billed_flere\">{more_images}</div>\r\n<div id=\"produkt_kasse_venstre_tekst\">Clik on the images to enlarge</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre\">\r\n<div class=\"produkt_kasse_hoejre_materialefarve\">\r\n<div class=\"produkt_kasse_hoejre_materialefarve_overskrift\">Select Material & Color</div>\r\n<div id=\"produkt_kasse_hojere_materialefarve_skubber\"></div>\r\n<div class=\"produkt_kasse_hoejre_materialefarve_indhold\">{product_attribute}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre_accessory\">{product_accessory}</div>\r\n<div class=\"produkt_kasse_hoejre_pris\">\r\n<div class=\"produkt_kasse_hoejre_pris_indre\" id=\"produkt_kasse_hoejre_pris_indre\">{product_price}</div>\r\n<div class=\"produkt_kasse_hoejre_pris_indre_inklmoms\">inc VAT</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre_laegikurv\">\r\n<div class=\"produkt_kasse_hoejre_laegikurv_indre\">{form_addtocart:add_to_cart2}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre_leveringstid\">\r\n<div class=\"produkt_kasse_hoejre_leveringstid_indre\">Delivery: {product_delivery_time}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre_bookmarksendtofriend\">\r\n<div class=\"produkt_kasse_hoejre_bookmark\">{bookmark}</div>\r\n<div class=\"produkt_kasse_hoejre_sendtofriend\">{send_to_friend}</div>\r\n</div>\r\n</div>\r\n<div class=\"produkt_beskrivelse_spacer\"></div>\r\n<div id=\"produkt_beskrivelse_wrapper\">\r\n<div class=\"produkt_beskrivelse\">\r\n<div id=\"produkt_beskrivelse_maal\">\r\n<div id=\"produkt_maal_wrapper\">\r\n<div id=\"produkt_maal_skubber_venstre\"></div>\r\n<div id=\"produkt_maal_indhold_hojre\">\r\n<div id=\"produkt_maal_overskrift\">\r\n<h3>Target of the product</h3>\r\n</div>\r\n<div id=\"produkt_hojde\">H: {hojde}</div>\r\n<div id=\"produkt_bredde\">x B: {bredde}</div>\r\n<div id=\"produkt_dybde\">x D: {dybde}</div>\r\n<div style=\"width: 275px; height: 10px; clear: left;\"></div>\r\n<div id=\"producent_link\">{manufacturer_link}</div>\r\n<div id=\"produkt_writereview\">{form_rating}</div>\r\n</div>\r\n</div>\r\n</div>\r\n<h2>{product_name}</h2>\r\n<div id=\"beskrivelse_lille\">{product_s_desc}</div>\r\n<div id=\"beskrivelse_stor\">{product_desc}</div>\r\n</div>\r\n</div>\r\n<div id=\"produkt_anmeldelser\">\r\n<div id=\"produkt_anmeldelser_headline\">\r\n<h3>Customer rating</h3>\r\n</div>\r\n{product_rating}</div>\r\n</div>\r\n</div>";
}

$payment_template = "";
$payment_template_desc = "";
$templatelist = $redTemplate->getTemplate("redshop_payment");

for ($i = 0; $i < count($templatelist); $i++)
{
	if (strstr($onestep_template_desc, "{payment_template:" . $templatelist[$i]->template_name . "}"))
	{
		$payment_template      = "{payment_template:" . $templatelist[$i]->template_name . "}";
		$payment_template_desc = $templatelist[$i]->template_desc;
		$onestep_template_desc = str_replace($payment_template, "<div id='divPaymentMethod'>" . $payment_template . "</div>", $onestep_template_desc);
	}
}
$templatelist = $redTemplate->getTemplate("checkout");

for ($i = 0; $i < count($templatelist); $i++)
{
	if (strstr($onestep_template_desc, "{checkout_template:" . $templatelist[$i]->template_name . "}"))
	{
		$cart_template         = "{checkout_template:" . $templatelist[$i]->template_name . "}";
		$onestep_template_desc = str_replace($cart_template, "<div id='divRedshopCart'>" . $cart_template . "</div><div id='divRedshopCartTemplateId' style='display:none'>" . $templatelist[$i]->template_id . "</div>", $onestep_template_desc);
		$onestep_template_desc = str_replace($cart_template, $templatelist[$i]->template_desc, $onestep_template_desc);
	}
}
// for shipping template
$shippingbox_template = "";
$shippingbox_template_desc = "";
$shipping_template = "";
$shipping_template_desc = "";

$templatelist = $redTemplate->getTemplate("shippingbox");

for ($i = 0; $i < count($templatelist); $i++)
{
	if (strstr($onestep_template_desc, "{shippingbox_template:" . $templatelist[$i]->template_name . "}"))
	{
		$shippingbox_template      = "{shippingbox_template:" . $templatelist[$i]->template_name . "}";
		$shippingbox_template_desc = $templatelist[$i]->template_desc;
	}
}

$templatelist = $redTemplate->getTemplate("redshop_shipping");

for ($i = 0; $i < count($templatelist); $i++)
{
	if (strstr($onestep_template_desc, "{shipping_template:" . $templatelist[$i]->template_name . "}"))
	{
		$shipping_template      = "{shipping_template:" . $templatelist[$i]->template_name . "}";
		$shipping_template_desc = $templatelist[$i]->template_desc;
		$onestep_template_desc  = str_replace($shipping_template, "<div id='divShippingRate'>" . $shipping_template . "</div><div id='divShippingRateTemplateId' style='display:none'>" . $templatelist[$i]->template_id . "</div>", $onestep_template_desc);
	}
}

if (SHIPPING_METHOD_ENABLE)
{

	if ($users_info_id > 0)
	{
		$ordertotal     = $cart['total'];
		$total_discount = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
		$order_subtotal = (SHIPPING_AFTER == 'total') ? $cart['product_subtotal'] - $total_discount : $cart['product_subtotal'];
//		$order_subtotal =$cart['product_subtotal'];

		$shippingbox_template_desc = $carthelper->replaceShippingBoxTemplate($shippingbox_template_desc, $shipping_box_post_id);
		$onestep_template_desc     = str_replace($shippingbox_template, $shippingbox_template_desc, $onestep_template_desc);

		$returnarr              = $carthelper->replaceShippingTemplate($shipping_template_desc, $shipping_rate_id, $shipping_box_post_id, $user->id, $users_info_id, $ordertotal, $order_subtotal);
		$shipping_template_desc = $returnarr['template_desc'];
		$shipping_rate_id       = $returnarr['shipping_rate_id'];

		if ($shipping_rate_id)
		{
			$shipArr              = $model->calculateShipping($shipping_rate_id);
			$cart['shipping']     = $shipArr['order_shipping_rate'];
			$cart['shipping_vat'] = $shipArr['shipping_vat'];
			$cart                 = $carthelper->modifyDiscount($cart);
		}
		$onestep_template_desc = str_replace($shipping_template, $shipping_template_desc, $onestep_template_desc);
	}
	else
	{
		$onestep_template_desc = str_replace($shippingbox_template, "", $onestep_template_desc);
		$onestep_template_desc = str_replace($shipping_template, JText::_('COM_REDSHOP_FILL_SHIPPING_ADDRESS'), $onestep_template_desc);
	}
}
else
{
	$onestep_template_desc = str_replace($shippingbox_template, "", $onestep_template_desc);
	$onestep_template_desc = str_replace($shipping_template, "", $onestep_template_desc);
}

// get billing info for check is_company
$is_company = $billingaddresses->is_company;

if ($billingaddresses->ean_number != "")
{
	$ean_number = 1;
}
if (strstr($onestep_template_desc, "{edit_billing_address}"))
{
	$editbill              = JRoute::_('index.php?option=' . $option . '&view=account_billto&tmpl=component&for=true&return=checkout&Itemid=' . $Itemid);
	$edit_billing          = '<a class="modal" href="' . $editbill . '" rel="{handler: \'iframe\', size: {x: 800, y: 550}}"> ' . JText::_('COM_REDSHOP_EDIT') . '</a>';
	$onestep_template_desc = str_replace("{edit_billing_address}", $edit_billing, $onestep_template_desc);
}
$onestep_template_desc = $carthelper->replaceBillingAddress($onestep_template_desc, $billingaddresses);

if (strstr($onestep_template_desc, "{shipping_address}"))
{
	if (SHIPPING_METHOD_ENABLE)
	{
		$shippingaddresses = $model->shippingaddresses();
		$shipp             = '';

		if ($billingaddresses && OPTIONAL_SHIPPING_ADDRESS)
		{
			$ship_check = ($users_info_id == $billingaddresses->users_info_id) ? 'checked="checked"' : '';
			$shipp .= '<div><input type="radio" onclick="javascript:onestepCheckoutProcess(this.name,\'\');" name="users_info_id" value="' . $billingaddresses->users_info_id . '" ' . $ship_check . ' />' . JText::_('COM_REDSHOP_DEFAULT_SHIPPING_ADDRESS') . '</div>';
		}
		for ($i = 0; $i < count($shippingaddresses); $i++)
		{
			$shipinfo = $shippingaddresses[$i];

			$edit_addlink = JRoute::_('index.php?option=' . $option . '&view=account_shipto&tmpl=component&for=true&task=addshipping&return=checkout&Itemid=' . $Itemid . '&infoid=' . $shipinfo->users_info_id);
			// $delete_addlink = JRoute::_( 'index.php?option='.$option.'&view=account_shipto&task=remove&return=checkout&infoid='.$shipinfo->users_info_id );
			$delete_addlink = $url . "index.php?option=" . $option . "&view=account_shipto&return=checkout&tmpl=component&task=remove&infoid=" . $shippingaddresses[$i]->users_info_id . "&Itemid=" . $Itemid;
			$ship_check     = ($users_info_id == $shipinfo->users_info_id) ? 'checked="checked"' : '';

			$shipp .= '<div><input type="radio" onclick="javascript:onestepCheckoutProcess(this.name,\'\');" name="users_info_id" value="' . $shipinfo->users_info_id . '" ' . $ship_check . ' />' . $shipinfo->firstname . " " . $shipinfo->lastname . " ";
			$shipp .= '<a class="modal" href="' . $edit_addlink . '" rel="{handler: \'iframe\', size: {x: 570, y: 470}}">(' . JText::_('COM_REDSHOP_EDIT_LBL') . ')</a> ';
			$shipp .= '<a href="' . $delete_addlink . '" title="">(' . JText::_('COM_REDSHOP_DELETE_LBL') . ')</a></div>';
		}
		$add_addlink = JRoute::_('index.php?option=' . $option . '&view=account_shipto&tmpl=component&for=true&task=addshipping&return=checkout&Itemid=' . $Itemid . '&infoid=0&is_company=' . $billingaddresses->is_company);
		$shipp .= '<a class="modal" href="' . $add_addlink . '" rel="{handler: \'iframe\', size: {x: 570, y: 470}}"> ' . JText::_('COM_REDSHOP_ADD_ADDRESS') . '</a>';
		$onestep_template_desc = str_replace('{shipping_address}', $shipp, $onestep_template_desc);
		$onestep_template_desc = str_replace('{shipping_address_information_lbl}', JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL'), $onestep_template_desc);
	}
	else
	{
		$onestep_template_desc = str_replace('{shipping_address}', '', $onestep_template_desc);
		$onestep_template_desc = str_replace('{shipping_address_information_lbl}', '', $onestep_template_desc);
	}
}
$payment_template_desc = $carthelper->replacePaymentTemplate($payment_template_desc, $payment_method_id, $is_company, $ean_number);
$onestep_template_desc = str_replace($payment_template, $payment_template_desc, $onestep_template_desc);

$onestep_template_desc = $model->displayShoppingCart($onestep_template_desc, $users_info_id, $shipping_rate_id, $payment_method_id, $Itemid);

$onestep_template_desc = '<form	action="' . JRoute::_('index.php?option=' . $option . '&view=checkout') . '" method="post" name="adminForm" id="adminForm"	enctype="multipart/form-data" onsubmit="return CheckCardNumber(this);">' . $onestep_template_desc . '<div style="display:none" id="responceonestep"></div></form>';

$onestep_template_desc = $redTemplate->parseredSHOPplugin($onestep_template_desc);
echo eval("?>" . $onestep_template_desc . "<?php ");?>
<script type="text/javascript">
	function chkvalidaion() {
		<?php
			if( MINIMUM_ORDER_TOTAL > 0 && $cart['total'] < MINIMUM_ORDER_TOTAL)
			{	?>
		alert("<?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_HAS_TO_BE_MORE_THAN');?>");
		return false;
		<?php
			}	?>
		if (document.getElementById('termscondition')) {
			var termscondition = document.getElementById('termscondition').checked;

			if (!termscondition) {
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_TEMS_CONDITIONS')?>");
				return false;
			}
		}
		return true;
	}
	function checkout_disable(val) {
		document.adminForm.submit();
		document.getElementById(val).disabled = true;
		var op = document.getElementById(val);
		op.setAttribute("style", "opacity:0.3;");

		if (op.style.setAttribute) //For IE
			op.style.setAttribute("filter", "alpha(opacity=30);");


	}
</script>