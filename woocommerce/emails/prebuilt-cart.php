<?php
/**
 * Customer Prebuilt Cart Email
 *
 *
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<p>
	<?php if ( $recipientname) { ?>
		Hi <?php echo $recipientname; ?>,<br/>
	<?php } ?>
	<?php if ( $message) { ?>
		<?php echo $message; ?>
	<?php } ?>

</p>
<p style="text-align: center;"><?php printf( esc_html__( 'Please click the link below to review your order and pay.', 'woocommerce' )); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>


<?php if ( $customlink) { ?>
	<a href="<?php echo $customlink; ?>" style="font-family: sans-serif; text-align: center; text-decoration: none;display: block; margin: 0 auto;padding: 20px;width: 250px;font-size: 1.25rem;background: #356e3e;border: 4px solid #75BD80;border-radius: 100px;color: #fff;">VIEW ORDER</a>
<?php } ?>

<?php

do_action( 'woocommerce_email_footer', $email );
