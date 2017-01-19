<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$uri = JURI::getInstance();
$url = $uri->root();
?>
<div class="panel panel-primary form-vertical">
    <div class="panel-heading">
        <h3><?php echo JText::_('COM_REDSHOP_MODULES_AND_FEATURES'); ?></h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label for="statistics_enable" class="hasTip"
                title="<?php echo JText::_('COM_REDSHOP_STATISTICS_ENABLE_TEXT') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_STATISTICS_ENABLE') ?>">
		        <?php echo JText::_('COM_REDSHOP_STATISTICS_ENABLE_TEXT') ?>
            </label>
	        <?php echo $this->lists['statistics_enable'] ?>
        </div>
        <div class="form-group">
            <label for="name" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_MY_WISHLIST_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MY_WISHLIST') ?>">
				<?php echo JText::_('COM_REDSHOP_MY_WISHLIST_LBL'); ?>
            </label>
			<?php echo $this->lists['my_wishlist'] ?>
        </div>
        <div class="form-group">
            <label for="invoice_mail_send_option" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_WISHLIST_LOGIN_REQUIRED_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WISHLIST_LOGIN_REQUIRED') ?>">
				<?php echo JText::_('COM_REDSHOP_WISHLIST_LOGIN_REQUIRED_LBL'); ?>
            </label>
			<?php echo $this->lists['wishlist_login_required'] ?>
        </div>
        <div class="form-group">
            <label for="my_tags" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_MY_TAGS_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MY_TAGS') ?>">
				<?php echo JText::_('COM_REDSHOP_MY_TAGS_LBL'); ?>
            </label>
			<?php echo $this->lists['my_tags'] ?>
        </div>
        <div class="form-group">
            <label for="enable_address_detail_in_shipping" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_ADDRESS_DETAIL_IN_SHIPPING_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_ADDRESS_DETAIL_IN_SHIPPING') ?>">
				<?php echo JText::_('COM_REDSHOP_ENABLE_ADDRESS_DETAIL_IN_SHIPPING_LBL'); ?>
            </label>
			<?php echo $this->lists['enable_address_detail_in_shipping'] ?>
        </div>
        <div class="form-group">
            <label for="is_product_reserve" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_USE_PRODUCT_RESERVE_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_PRODUCT_RESERVE_LBL') ?>">
				<?php echo JText::_('COM_REDSHOP_USE_PRODUCT_RESERVE_LBL'); ?>
            </label>
			<?php echo $this->lists['is_product_reserve'] ?>
        </div>
        <div class="form-group">
            <label for="cart_reservation_message" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CART_RESERVATION_MESSAGE_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CART_RESERVATION_MESSAGE') ?>">
				<?php echo JText::_('COM_REDSHOP_CART_RESERVATION_MESSAGE_LBL'); ?>
            </label>
            <textarea class="form-control" type="text" name="cart_reservation_message"
                      id="cart_reservation_message" rows="4"
                      cols="40"/><?php echo stripslashes($this->config->get('CART_RESERVATION_MESSAGE')); ?>
            </textarea>
        </div>
    </div>
</div>
