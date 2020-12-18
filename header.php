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
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<link rel="preload" as="font" href="<?php echo get_stylesheet_directory_uri();?>/fonts/librebaskerville-bold-webfont.woff2" type="font/woff2" crossorigin="anonymous">
	<link rel="preload" as="font" href="<?php echo get_stylesheet_directory_uri();?>/fonts/linearicons.woff" type="font/woff" crossorigin="anonymous">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div class="site" id="page">

	<!-- ******************* The Navbar Area ******************* -->
	<div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">

		<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'understrap' ); ?></a>

		<nav class="navbar navbar-expand-md">
			<div class="mobile-nav d-md-none">
				<?php shiftnav_toggle( 'shiftnav-main' , '' , array( 'icon' => 'bars' , 'class' => 'shiftnav-toggle-button') ); ?>
			</div>
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
					<img id="logo-svg" src="<?php echo get_stylesheet_directory_uri() .'/images/hennepens-logo-registered.svg';?>" alt="Hennepen's" width="300">
				</a>
				<span class="tagline">Hemp Made</span>
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
			        <li class="m-none d-flex"><a href="/my-account" class="d-flex"><i class="icon-user"></i></a></li>
			      <?php } ?>
			      <li><?php echo do_shortcode('[xoo_wsc_cart]'); ?></li>
			  </ul>
		  </div>
			<!-- The WordPress Menu goes here -->
		</nav><!-- .site-navigation -->

	</div><!-- #wrapper-navbar end -->
