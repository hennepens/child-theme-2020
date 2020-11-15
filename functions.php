<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

add_action( 'init', 'child_remove_parent_functions', 99 );
function child_remove_parent_functions() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
}

add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {
    
    // Adds the new tab
    
    $tabs['test_tab'] = array(
        'title'     => __( 'Certificate of Analysis', 'woocommerce' ),
        'priority'  => 20,
        'callback'  => 'woo_coa_tab_content'
    );

    return $tabs;

}
function woo_coa_tab_content() {
    $pdf = get_field('coa_pdf_upload'); 
    if(!empty($pdf)){
        echo '<a href="'. $pdf['url'] .'"><i class="fa fa-lg fa-file-pdf-o"></i> Download Certificate of Analysis</a>';
    }
    $image = get_field('coa_image_upload'); 
    if( !empty($image) ){ ?>
        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" /><?php
    }
}


function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



remove_action( 'wp_footer', 'woocommerce_demo_store' );
add_action( 'wp_body_open', 'woocommerce_demo_store' );


function category_banner(){ ?>
    <?php if(get_field('category_banner_image')){?>
     <div class="cat-header position-relative" style="background-image: url(<?php the_field('category_banner_image'); ?>);">
     </div>
     <?php } ?>
    
<?php }

add_action('woocommerce_archive_description','category_banner',15);


add_filter( 'woocommerce_variable_price_html', 'bbloomer_variation_price_format', 10, 2 );
 
function bbloomer_variation_price_format( $price, $product ) {
 
// 1. Get min/max regular and sale variation prices
 
$min_var_reg_price = $product->get_variation_regular_price( 'min', true );
$min_var_sale_price = $product->get_variation_sale_price( 'min', true );
$max_var_reg_price = $product->get_variation_regular_price( 'max', true );
$max_var_sale_price = $product->get_variation_sale_price( 'max', true );
 
// 2. New $price, unless all variations have exact same prices
 
if ( ! ( $min_var_reg_price == $max_var_reg_price && $min_var_sale_price == $max_var_sale_price ) ) {   
   if ( $min_var_sale_price < $min_var_reg_price ) {
      $price = sprintf( __( '<del>%1$s</del><ins>%2$s</ins>', 'woocommerce' ), wc_price( $min_var_reg_price ), wc_price( $min_var_sale_price ) );
   } else {
      $price = sprintf( __( '%1$s', 'woocommerce' ), wc_price( $min_var_reg_price ) );
   }
}
 
// 3. Return $price
 
return $price;
}

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] );   // Remove the additional information tab

    return $tabs;
}

/**
 * Display the Autoship Product Options as radio buttons
 * @param WC_Product $product The current WC Product object.
 */
function xx_display_autoship_radio_options( $product ){

  ?>

  <label class="autoship-label" for="autoship-no">
    <input type="radio" class="autoship-options autoship-radio-option" name="autoship-options" value="" checked="checked"/>
    <?php echo __( 'One-time Purchase', 'autoship' ); ?>
  </label>

  <div class="autoship-save-options">

  <h3><?php echo autoship_checkout_recurring_discount_string( $product->get_id() ); ?></h3>

  <?php
  // Loop through the Scheduled Options and display as radio options.
  foreach ( autoship_product_frequency_options( $product->get_id() ) as $key => $option ): ?>

    <label class="autoship-label" for="autoship-option-<?php echo $key;?>">
      <input type="radio" class="autoship-options autoship-radio-option" name="autoship-options" value="<?php echo esc_attr( json_encode( $option ) ); ?>" />
      <?php echo esc_html( $option['display_name'] ); ?>
    </label>

  <?php endforeach; ?>

  </div>

  <?php

}

/*
add_action('autoship_before_schedule_options', 'xx_display_autoship_radio_options', 10, 1 );
add_action('autoship_before_schedule_options_variable', 'xx_display_autoship_radio_options', 10, 1 );

function autoship_new_default_frequency_options( $options ) {
    // Return a new set of default frequency options of 30, 60, 90 Days
    return array(
        array(
            // Days, Weeks, Months, DayOfTheWeek, DayOfTheMonth
            'frequency_type' => 'Days',
            // Frequency (integer)
            'frequency' => 30,
            'display_name' => 'Monthly'
        ),
        array(
            // Days, Weeks, Months, DayOfTheWeek, DayOfTheMonth
            'frequency_type' => 'Days',
            // Frequency (integer)
            'frequency' => 60,
            'display_name' => 'Bi-Monthly'
        ),
        array(
            // Days, Weeks, Months, DayOfTheWeek, DayOfTheMonth
            'frequency_type' => 'Days',
            // Frequency (integer)
            'frequency' => 90,
            'display_name' => 'Quarterly'
        )
    );
}
add_filter( 'autoship-default-frequency-options', 'autoship_new_default_frequency_options' ); */

if ( ! function_exists( 'understrap_wc_form_field_args' ) ) {
  // This function replaces the Understrap function of the same name
  function understrap_wc_form_field_args( $args, $key, $value = null ) {
    return $args;
  }
}


?>