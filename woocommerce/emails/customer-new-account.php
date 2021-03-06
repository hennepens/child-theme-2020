<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email ); 
$customer = get_user_by('login', $user_login );?>
<h3><?php printf( esc_html__( 'Thanks for registering on hennepens.com!'));?></h3><br/>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $customer->user_firstname ) ); ?></p>
<p><?php printf( esc_html__( 'You can now access your account area to view orders, change your password, see your subscription status, and more at: %2$s', 'woocommerce' ), esc_html( $blogname ), make_clickable( esc_url( wc_get_page_permalink( 'myaccount' ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<p><strong>username:</strong>

<?php


printf( esc_html( $customer->user_email ) ); ?>

<br/>
<?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) : ?>
  <?php /* translators: %s Auto generated password */ ?>
  <strong>password: </strong><?php printf( esc_html( $user_pass ) ); ?>
<?php endif; ?>
<br/>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
