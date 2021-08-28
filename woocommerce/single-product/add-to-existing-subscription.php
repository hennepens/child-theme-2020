<?php
/**
 * Form used for add to existing subscription from the single product page.
 *
 * @author      Shop Plugins
 * @category    WooCommerce Subscriptions/Templates
 * @version     2.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $product->is_purchasable() ) {
	return;
}
?>
<form action="#" method="POST" class="jgtb-add-to-subscription">
	<label for="jgtb_add_to_existing"><?php esc_html_e( 'Add to existing subscription?', 'jg-toolbox' ); ?></label>
	<select name="jgtb_add_to_existing_subscription" id="jgtb_add_to_existing">
		<option value="null">
			<?php echo esc_html_x( '-- Select an existing subscription --', 'default option in dropdown in add to existing subscription template', 'jg-toolbox' ); ?>
			</option>
		<?php
		foreach ( $subscriptions as $subscription ) {
			echo wp_kses( sprintf( '<option value="%s">%s</option>', $subscription->get_id(), Javorszky\Toolbox\Utilities\generate_option_text( $subscription ) ), array( 'option' => array( 'value' => array() ) ) );
		}
		unset( $subscription );
		?>
	</select>
	<?php
	foreach ( $subscriptions as $subscription ) {
		$items_string = Javorszky\Toolbox\Utilities\generate_nonce_on_items( $subscription );
		wp_nonce_field( 'add_to_subscription_' . $items_string, 'jgtbwpnonce_' . $subscription->get_id(), false );
	}


	?>
	<script>document.getElementById("atsval").value = 1;</script>
	<input type="hidden" id="atsval"  name="ats_quantity" value="" class="ats_qty">
	<input type="hidden" name="ats_product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
	<input type="hidden" name="ats_variation_id" value="0">
	<input type="hidden" name="ats_variation_attributes" value="">
	<button type="submit" name="add-to-subscription" disabled="disabled" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
		<?php echo esc_html_x( 'Add to existing subscription', 'Text on button for add to existing subscription functionality', 'jg-toolbox' ); ?>
	</button>
</form>
