<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
$upload_dir = wp_get_upload_dir();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="icon" type="image/x-icon"  href="hennepens.ico">
	<link rel="preconnect" href="https://f8p2j7h2.rocketcdn.me">
	<link rel="dns-prefetch" href="https://f8p2j7h2.rocketcdn.me">
	<link rel="preload" as="font" href="<?php echo get_stylesheet_directory_uri();?>/fonts/superior-regular-webfont.woff2" crossorigin="anonymous">
	<link rel="preload" as="font" href="<?php echo get_stylesheet_directory_uri();?>/fonts/librebaskerville-bold-webfont.woff2" crossorigin="anonymous">
	<link rel="preload" as="font" href="<?php echo get_stylesheet_directory_uri();?>/fonts/librebaskerville-regular-webfont.woff2" crossorigin="anonymous">
	<link rel="preload" as="font" href="<?php echo get_stylesheet_directory_uri();?>/fonts/librebaskerville-italic-webfont.woff2" crossorigin="anonymous">
	<?php 
	if(is_front_page()){
	 if(!wp_is_mobile()){ ?>
		<link rel="preload" as="image" href="/wp-content/uploads/2020/12/hennepens-colorado-fields.jpg" type="image/webp">
	<?php }else{ ?>
		<link rel="preload" as="image" href="/wp-content/uploads/2020/12/hennepens-hero-image-mobile.jpg" type="image/webp">
	<?php } 
	}
	?>
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div class="site" id="page">

	<!-- ******************* The Navbar Area ******************* -->
	<div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">

		<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'understrap' ); ?></a>

		<nav class="navbar navbar-expand-md">
				<div class="d-flex container">
				<?php wp_nav_menu(
				array(
					//'theme_location'  => 'primary',
					'container_class' => 'collapse navbar-collapse right',
					'container_id'    => 'navbarNavDropdown',
					'menu_class'      => 'navbar-nav ml-auto',
					'fallback_cb'     => '',
					'menu'         => 'Shop Menu',
					'depth'           => 2,
					'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
				)
			); ?>
			<h1 class="navbar-brand mb-0 ">
				<a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url">
					<?php if(is_woocommerce() || is_account_page() || is_page('wellness-network') || is_page('contact-us') || is_page('contact') ||  is_page('privacy-policy') || is_page('shipping-returns') || is_page('terms-of-service') || is_page('faq') || is_page('certificates-of-analysis') ||   is_cart()){ ?>
						<img id="logomark-svg" class="mb-3" alt data-lazy-type="image" src="<?php echo get_stylesheet_directory_uri(); ?>/images/hennepens-logomark-no-register.svg" alt="Hennepen's" width="80" height="80">
					<?php }else{ ?>
					<img id="logo-svg" src="<?php echo get_stylesheet_directory_uri() .'/images/hennepens-logo-tagline.svg';?>" alt="Hennepen's" width="300">
					<?php } ?>

				</a>
			</h1>
			<!--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="<?php/* esc_attr_e( 'Toggle navigation', 'understrap' );*/ ?>">
				<span class="navbar-toggler-icon"></span>
			</button>-->
			<?php wp_nav_menu(
				array(
					//'theme_location'  => 'primary',
					'container_class' => 'collapse navbar-collapse right',
					'container_id'    => 'navbarNavDropdown',
					'menu_class'      => 'navbar-nav mr-auto',
					'fallback_cb'     => '',
					'menu'         => 'Main Menu (Right)',
					'depth'           => 2,
					'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
				)
			); ?>
		</div>
		
			<div class="quick-links-container">
				<ul class="navbar-nav">
			      <?php if ( is_user_logged_in() ) {
			      wp_get_current_user();
			      echo '<li><a href="/my-account">' . get_avatar( $current_user ) . '</a></li>'; ?>		    
			      <?php } else { ?>
			        <li class="d-none d-md-flex"><a href="/login">My Account</a></li>
			      <?php } ?>
			      <li><?php echo do_shortcode('[xoo_wsc_cart]'); ?></li>
			  </ul>
		  </div>
			<!-- The WordPress Menu goes here -->
		</nav><!-- .site-navigation -->

	</div><!-- #wrapper-navbar end -->
