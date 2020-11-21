<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
  wp_dequeue_style( 'understrap-styles' );
  wp_deregister_style( 'understrap-styles' );

  wp_dequeue_script( 'understrap-scripts' );
  wp_deregister_script( 'understrap-scripts' );

  // Removes the parent themes stylesheet and scripts from inc/enqueue.php
  wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
  wp_enqueue_script( 'jquery');
  wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
      wp_enqueue_script( 'comment-reply' );
  }
  if ( is_singular() && is_product() ) {
    wp_enqueue_script( 'custom-schedule-options', get_stylesheet_directory_uri() . '/js/custom-schedule-options.js', array(), $the_theme->get( 'Version' ), true );
  }

  if( ! function_exists( 'is_product' ) || ! is_product() ) { return; }
  wp_enqueue_script( 'jquery' );
  wp_add_inline_script( 'jquery', '
    jQuery( document ).ready( function( $ ) {
      $( ".variations_form" ).on( "wc_variation_form woocommerce_update_variation_values", function() {
        $( "div.generatedRadios" ).remove();
        $( "table.variations select" ).each( function() {
          var selName = $( this ).attr( "name" );
          $( "select[name=" + selName + "] option" ).each( function() {
            var option = $( this );
            var value = option.attr( "value" );
            if( value == "" ) { return; }
            var label = option.html();
            var select = option.parent();
            var selected = select.val();
            var isSelected = ( selected == value ) ? " checked=\"checked\"" : "";
            var radioHtml = `<input type="radio" name="${selName}" value="${value}"${isSelected}>`;
            var optionHtml = `<div class="generatedRadios"><label>${radioHtml} ${label}</label></div>`;
            select.parent().append(
              $( optionHtml ).click( function() {
                select.val( value ).trigger( "change" );
              } )
            )
          } ).parent().hide();
        } );
      } );
    } );
  ', 'after'
  );
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


if ( ! function_exists( 'understrap_wc_form_field_args' ) ) {
  // This function replaces the Understrap function of the same name
  function understrap_wc_form_field_args( $args, $key, $value = null ) {
    return $args;
  }
}


/*
 * Product subscriptions
 */

function get_company_name($echo = false){

  if (function_exists('get_field'))
    $output = get_field('company', 'option');

  if (empty($output))
    $output = get_bloginfo('name');

  if ($echo) echo $output;
  else return $output;
}


function get_help_icon($content, $type = 'text', $echo = false){

  if ($type == 'image') {

    $class = 'covering-image';
    $content = "<img src='$content' alt='' />";

  } else $class = 'with-paddings';

  $output = "<span class='help-icon'>\n".
    "<span class='help-icon-inner fa fa-question-circle'></span>\n".
    ($content ? "<span class='help-icon-hover $class'><span class='help-icon-hover-inner'>$content</span></span>\n" : "").
    "</span>\n";

  if ($echo) echo $output;
  else return $output;
}

/*
 * Product subscriptions: Cart
 */

// Remove filters added by "WC Subscriptions" and "WC All Products For Subscriptions"
remove_filter( 'woocommerce_cart_item_price', array( 'WCS_ATT_Display_Cart', 'show_cart_item_subscription_options' ), 1000, 3 );
remove_filter( 'woocommerce_cart_item_subtotal', array( 'WC_Subscriptions_Switcher', 'add_cart_item_switch_direction' ), 10, 3 );


add_filter('woocommerce_reset_variations_link', '__return_empty_string');


?>