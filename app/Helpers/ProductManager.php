<?php
/**
 * Developed by Caspian Soft.
 *
 * Description:
 *
 * @author Orkhan Alirzayev
 * @date 2/12/18
 */


function getDiscount($product_price, $discount)
{
    $discounted_price = $product_price - ($product_price * ($discount / 100));
    return $discounted_price;
}
