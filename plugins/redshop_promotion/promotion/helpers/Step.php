<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once ('Helper.php');

/**
 * Cart helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Step
{
    public static function applyPromotion(&$promotion, &$cart) {
        $data = new \stdClass();

        if (isset($promotion->data)) {
            $data =& $promotion->data;
        }

        $data->promotion_type = $data->promotion_type ?? '';

        /*
         * Step 1: check is promotion applied or not
         * Step 2:  if Yes, check is satisfied condition, if NO, remove and mark it's not applied
         *          else No, check is satisfied condition, if YES, add up and mark it's applied
         */

        $promotion->isApplied = $promotion->isApplied ?? false;

        switch($data->promotion_type) {
            case 'amount_product':
                //echo 'here'; die;
                if (!$promotion->isApplied && Helper::getConditionProductAmount($data, $cart)) {
                    self::applyPromotionProductAmount($promotion, $cart);
                } else {
                    self::removePromotion($promotion, $cart);
                }
                break;
            case 'volume_order':
                if (!$promotion->isApplied && Helper::getConditionOrderVolume($data, $cart)) {
                    self::applyPromotionVolumeOrder($promotion, $cart);
                } else {
                    self::removePromotion($promotion, $cart);
                }

                break;
            default:
                break;
        }
    }

    /**
     * @param $promotion
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    public static function applyPromotionProductAmount(&$promotion, &$cart) {
        self::applyPromotionVolumeOrder($promotion,$cart);
    }

    /**
     * @param $promotion
     * @param $cart
     * @since  __DEPLOY_VERSION__
     */
    protected static function applyPromotionVolumeOrder(&$promotion, &$cart) {
            $promotion->isApplied = true;
            $idx = $cart['idx']++;
            $productAwardId = $promotion->data->product_award ?? 0;
            $productAwardAmount = $promotion->data->award_amount ?? 0;
            $product = \Redshop\Product\Product::getProductById($productAwardId);

            $award = [
                'hidden_attribute_cartimage' => '',
                'product_price_excl_vat' => 0.0,
                'subscription_id' => 0,
                'product_vat' => 0,
                'giftcard_id' => '',
                'product_id' => $productAwardId,
                'discount_calc_output' => '',
                'discount_calc' => [],
                'product_price' => 0.0,
                'product_old_price' => 0.0,
                'product_old_price_excl_vat' => 0.0,
                'cart_attribute' => [],
                'cart_accessory' => [],
                'quantity' => $productAwardAmount ?? 1,
                'category_id' => $product->category_id ?? 0,
                'wrapper_id' => 0,
                'wrapper_price' => 0.0,
                'isPromotionAward' => true,
                'promotion_id' => $promotion->id
            ];

            $cart[$idx] = $award;
    }

    /**
     * @param $promotion
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    public static function removePromotion(&$promotion, &$cart) {
        $unCount = 0;
        for ($i = 0; $i < $cart['idx']; $i++) {

            while (isset($cart[$i]['promotion_id']) &&
                ($cart[$i]['promotion_id'] == $promotion->id)) {
                $unCount++;
                $promotion->isApplied = false;

                if (isset($cart[$i + 1])) {
                    $cart[$i] = $cart[$i + 1];
                } else {
                    unset($cart[$i]);
                }
            }
        }

        if ($unCount > 0) {
            $cart['idx'] = $cart['idx'] - $unCount;
        }
    }

    /**
     * @param $cart
     * @return array|mixed
     * @since  __DEPLOY_VERSION__
     */
    public static function getPromotionsFromCart(&$cart) {
        $cart['promotions'] = $cart['promotions'] ?? [];

        return $cart['promotions'];
    }
}