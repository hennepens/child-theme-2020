<?php
/**
 * Product Subscription Options Template.
 *
 * Override this template by copying it to 'yourtheme/woocommerce/single-product/product-subscription-options.php'.
 *
 * On occasion, this template file may need to be updated and you (the theme developer) will need to copy the new files to your theme to maintain compatibility.
 * We try to do this as little as possible, but it does happen.
 * When this occurs the version of the template file will be bumped and the readme will list any important changes.
 *
 * @version 3.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php

// exit if accessed directly
if (!defined('ABSPATH')) exit;

$subscription_options = $hidden_options = array();

$tip_text = sprintf(__('We’ll ship your favorite %s products based on the schedule that you select. This way, you will never run out. You can change the schedule, pause, or cancel anytime.'), get_company_name());

//$product_price = $product->get_price_html();
foreach ($options as $option) {

	// visible controls
	if ($option['value'] == 0) {

		$one_time_option = "<li". ($option['selected'] ? " class='selected'" : "") .">".
			"<input type='radio' id='one-time-purchase' name='purchase-options' value='one-time'". ($option['selected'] ? " checked" : "") ." />".
			"<label class='one-time-purchase-label' for='one-time-purchase'>". __('Purchase One-Time') . "<span class='one-time-price'></span></label></li>\n";

	} else {

		if ($option['selected'])
			$selected_subscription_option = $option['selected'];

		$subscription_options[] = "<option value='". $option['value'] ."'selected ". ($option['selected'] ? " selected" : "") .">".
			sprintf('%d %s', $option['data']['subscription_scheme']['interval'], ucfirst($option['data']['subscription_scheme']['period'])) ."s</option>\n";
	}

	// hidden controls
	$hidden_options[] = sprintf('<li class="%1$s'. ($option['selected'] ? " selected" : "") .'"><label><input type="radio" name="convert_to_sub_%2$d" data-custom_data="%3$s" value="%4$s" %5$s autocomplete="off" />'.
		'<span class="%1$s-details">%6$s</span></label></li>',
			esc_attr($option['class']),
			absint($product_id),
			esc_attr(json_encode($option['data'])),
			esc_attr($option['value']),
			checked($option['selected'], true, false),
			$option['description']
	); 
}
//$amount_saved = $product->get_sale_price();
echo
"<div class='qty-container'><label>Select a Quantity</label><select id='selectQty' class='input-text qty'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option></select></div><label>I would like to</label><ul class='purchase-options'>\n".
	(isset($one_time_option) ? $one_time_option : "").

	($subscription_options ? "<li". (isset($selected_subscription_option) ? " class='selected'" : "") .">".
		"<input type='radio' id='subscriptions-list' name='purchase-options' value='subscription'". (isset($selected_subscription_option) ? " checked" : "") ." />\n".
		"<label for='subscriptions-list' class='subscription-container'><span class='choose-label'>". ($prompt ? strip_tags($prompt) : __('Subscribe & Save 15%')) . "<ul class='subscription-price'>". implode('', $hidden_options) ."</ul></label>\n".
		"<div class='delivery-container'><h6 class='prompt-heading'>Choose a Subscription Cycle</h6><div class='subscription-delivery'><label class='delivery-every'>Deliver <span class='current_selected_qty'>1</span> <span class='current_selected_variant'></span> every</label><select name='subscription-options'>". implode('', $subscription_options) ."</select>\n" : "").
		get_help_icon($tip_text) ."</div></div></li>\n".
"</li>".
"</ul>\n" .

"<div class='wcsatt-options-wrapper'>\n".

	"<ul class='wcsatt-options-product'>\n".
		implode('', $hidden_options).
	"</ul>\n".

"</div>\n";
