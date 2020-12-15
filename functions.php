<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


//Page Slug Body Class
function add_slug_body_class( $classes ) {
global $post;
if ( isset( $post ) ) {
$classes[] = $post->post_type . '-' . $post->post_name . " fade-out";
}
return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );
    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );
    wp_dequeue_script( 'wc-cart-fragments' ); 

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );


add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'font-awesome' ); // FontAwesome 4
    wp_dequeue_style( 'font-awesome-5' ); // FontAwesome 5

    wp_dequeue_style( 'jquery-magnificpopup' );
    wp_dequeue_script( 'jquery-magnificpopup' );

    wp_dequeue_script( 'bootstrap' );
    wp_dequeue_script( 'imagesloaded' );
    wp_dequeue_script( 'jquery-fitvids' );
    wp_dequeue_script( 'jquery-throttle' );
    wp_dequeue_script( 'jquery-waypoints' );
    wp_dequeue_script( 'jquery-blockui' );
    wp_dequeue_script( 'jquery-placeholder' );
    wp_dequeue_script( 'fancybox' );
    wp_dequeue_script( 'jqueryui' );
    wp_dequeue_style( 'woocommerce-smallscreen' );
    wp_dequeue_style( 'woocommerce_fancybox_styles' );

}, 9999 );




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
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

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
//remove_filter( 'woocommerce_cart_item_subtotal', array( 'WC_Subscriptions_Switcher', 'add_cart_item_switch_direction' ), 10, 3 );
  add_action( 'wp_enqueue_scripts', function() {
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
            if(isSelected.includes("checked")){var selectedClass = "selected"} else{var selectedClass=""};
            var radioHtml = `<input type="radio" name="${selName}" value="${value}" ${isSelected}>`;
            var optionHtml = `<div class="generatedRadios ${selectedClass}"><label>${radioHtml} ${label}</label></div>`;
            select.parent().append(
              $( optionHtml ).click( function() {
                select.val( value ).trigger( "change" );
              } )
            )
          } ).parent().hide();
        } );
      } );
    } );
  ', 'after' );
} );

add_filter('woocommerce_reset_variations_link', '__return_empty_string');


add_action( 'woocommerce_before_add_to_cart_quantity', 'func_option_valgt2' );
function func_option_valgt2() {
    global $product;

    if($product->is_type('variable')){
        $variations_data = [];
        foreach($product->get_available_variations() as $variation ){
            // Variation ID
             $variations_data[$variation['variation_id']] = $variation['display_regular_price'];
            // Prices
            $active_price = floatval($variation['display_price']); // Active price
            $regular_price = floatval($variation['display_regular_price']); // Regular Price
            if( $active_price != $regular_price ){
                $sale_price = $active_price; // Sale Price
                $variations_data[$variation['variation_id']] = $sale_price;
                $variations_orig_data[$variation['variation_id']] = $regular_price;
            }
        }
?>
        <script>
        jQuery(function($) {
          const formatToCurrency = amount => {
            return "$" + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
          }
          var jsonData = <?php echo json_encode($variations_data); ?>,
              inputVID = 'input.variation_id',
              regular_price = <?php echo json_encode($variations_orig_data); ?>;

          $('input').change( function(){
              if( '' != $(inputVID).val() ) {
                  var vid      = $(inputVID).val(), // VARIATION ID
                      vprice   = '',
                      rprice   = ''; // Initilizing


                  // Loop through variation IDs / Prices pairs
                  $.each( jsonData, function( index, price) {
                      if( index == $(inputVID).val() ) {
                          vprice = formatToCurrency(price); // The right variation price
                      }
                  });
                  $.each( regular_price, function(index, regular_price) {
                      if( index == $(inputVID).val() ) {
                          rprice = formatToCurrency(regular_price); // The right variation price
                          
                      }
                  });
                  $('.one-time-price').html("<del>"+ rprice + "</del>" + " " + vprice);
                  //alert('variation Id: '+vid+' | Lengde: '+length+' | Diameter: '+diameter+' | Variantpris: '+vprice);
              }
          });
        });
        </script>
        <?php
    }
  }

add_filter( 'gettext', 'bbloomer_translate_woocommerce_strings', 999, 3 );
  
function bbloomer_translate_woocommerce_strings( $translated, $untranslated, $domain ) {
 
   if ( ! is_admin() && 'woocommerce' === $domain ) {
 
      switch ( $translated) {
 
         case 'Deliver every' :
 
            $translated = 'On Offer';
            break;
 
         case 'Description' :
 
            $translated = 'Product Specifications';
            break;
 
         // ETC
       
      }
 
   }   
  
   return $translated;
 
}

function add_info_before_container(){
  echo '<div class="product-info">';
}

add_action('woocommerce_before_shop_loop_item_title', 'add_info_before_container');

function add_info_after_container(){
  echo '</div>';
}

add_action('woocommerce_after_shop_loop_item', 'add_info_after_container');

add_filter( 'wcsatt_price_html_suffix', 'apfs_remove_suffix', 10, 3 );

function apfs_remove_suffix( $suffix, $product, $args ) {
  return '';
}


add_action('woocommerce_after_customer_login_form', 'login_show_hide');
function login_show_hide(){
    echo '<h4 class="lines text-center"><span>New to Hennepen\'s?</span></h4><br /><a href="#" class="btn btn-outline-secondary m-auto d-block login-toggle align-self-center" id="sign-up-btn" style="width: 200px;"><span>Sign Up Now</span></a>
    <script>
    window.onload=function(){
      if (window.location.href.indexOf("register") > -1) {
        document.getElementById("sign-up-btn").click();
      }
    };
    </script> 
    ';
}

function hook_additional_product_info_template() {
  global $product;
  if ( has_term( 'softgel-template', 'product_tag' ) ) {
    echo '<div class="bb-content">' . do_shortcode( '[fl_builder_insert_layout slug="softgels"]' ) . '</div>';
  }
  if ( has_term( 'sports-cream-template', 'product_tag' ) ) {
    echo '<div class="bb-content">' . do_shortcode( '[fl_builder_insert_layout slug="sports-cream"]' ) . '</div>';
  }
  if ( has_term( 'better-balm-template', 'product_tag' ) ) {
    echo '<div class="bb-content">' . do_shortcode( '[fl_builder_insert_layout slug="better-balm"]' ) . '</div>';
  }
  if ( has_term( 'isolate-tincture-template', 'product_tag' ) ) {
    echo '<div class="bb-content">' . do_shortcode( '[fl_builder_insert_layout slug="isolate-tincture"]' ) . '</div>';
  }
  if ( has_term( 'full-spectrum-tincture-template', 'product_tag' ) ) {
    echo '<div class="bb-content">' . do_shortcode( '[fl_builder_insert_layout slug="full-spectrum-tincture"]' ) . '</div>';
  }
  else{
    //echo 'HELLO';
  }
}

add_action( 'woocommerce_after_single_product', 'hook_additional_product_info_template' );

function the_dramatist_price_show() {
    global $product;
    if( $product->is_on_sale() ) {
        return $product->get_sale_price();
    }
    return $product->get_regular_price();
}

add_filter('woocommerce_sale_flash', 'lw_hide_sale_flash');
function lw_hide_sale_flash(){
  return false;
}

//add_action( 'woocommerce_before_single_product', 'bbloomer_prev_next_product' );
 
// and if you also want them at the bottom...
//add_action( 'woocommerce_after_single_product', 'bbloomer_prev_next_product' );
 
function bbloomer_prev_next_product(){
 
echo '<div class="prev_next_buttons">';
  $prevPost = get_previous_post(true);
  $nextPost = get_next_post(true);

  if($prevPost) {
    $prevthumbnail = get_the_post_thumbnail($prevPost->ID, array(100,100) );
  }else{
    $prevthumbnail = '';
  }
  if($nextPost){
    $nextthumbnail = get_the_post_thumbnail($nextPost->ID, array(100,100) );
  }else{
    $nextthumbnail = '';
  }
   // 'product_cat' will make sure to return next/prev from current category
   $previous = next_post_link('%link', '&larr; %prevthumbnail  %title ', TRUE, ' ', 'product_cat');
   $next = previous_post_link('%link', '%title nextthumbnail  &rarr;', TRUE, ' ', 'product_cat');
 
   echo $previous;
   echo $next;
    
echo '</div>';
         
}

add_action( 'woocommerce_after_single_product', 'wpsites_image_nav_links' );

function wpsites_image_nav_links() {

if( !is_singular('product') ) 
      return;

if( $prev_post = get_previous_post() ): 
echo'<span class="single-post-nav previous-post-link">';
$prevpost = get_the_post_thumbnail( $prev_post->ID, 'medium', array('class' => 'pagination-previous')); 
previous_post_link( '%link',"$prevpost  <p>Previous Post in Category</p>", TRUE ); 
echo'</span>';
endif; 

if( $next_post = get_next_post() ): 
echo'<span class="single-post-nav next-post-link">';
$nextpost = get_the_post_thumbnail( $next_post->ID, 'medium', array('class' => 'pagination-next')); 
next_post_link( '%link',"$nextpost  <p>Next Post in Category</p>", TRUE ); 
echo'</span>';
endif; 
} 

add_filter( 'woocommerce_account_menu_items', 'custom_remove_downloads_my_account', 999 );
 
function custom_remove_downloads_my_account( $items ) {
unset($items['downloads']);
return $items;
}

function iconic_add_to_cart_button_text( $text, $product ) {
  return '<div class="woocommerce-button" title="' . esc_attr__( 'Select options', 'woocommerce' ) . '">Add to Cart</div>';
}

add_filter( 'iconic_wssv_add_to_cart_button_text', 'iconic_add_to_cart_button_text', 10, 2 );

add_action( 'woocommerce_after_shop_loop_item', 'misha_after_add_to_cart_btn' );
 
function misha_after_add_to_cart_btn(){
  global $product;
  echo '<a class="subscription-message" href="'. get_permalink( $productUrl->ID ) .'">Subscribe &amp; Save 40&percnt;</a>';
}

function woocommerce_quantity_input( $args = array(), $product = null, $echo = true ) {
  
   if ( is_null( $product ) ) {
      $product = $GLOBALS['product'];
   }
 
   $defaults = array(
      'input_id' => uniqid( 'quantity_' ),
      'input_name' => 'quantity',
      'input_value' => '1',
      'classes' => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ),
      'max_value' => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
      'min_value' => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
      'step' => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
      'pattern' => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
      'inputmode' => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
      'product_name' => $product ? $product->get_title() : '',
   );
 
   $args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );
  
   // Apply sanity to min/max args - min cannot be lower than 0.
   $args['min_value'] = max( $args['min_value'], 0 );
   // Note: change 20 to whatever you like
   $args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : 20;
 
   // Max cannot be lower than min if defined.
   if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
      $args['max_value'] = $args['min_value'];
   }
  
   $options = '';
    
   for ( $count = $args['min_value']; $count <= $args['max_value']; $count = $count + $args['step'] ) {
 
      // Cart item quantity defined?
      if ( '' !== $args['input_value'] && $args['input_value'] >= 1 && $count == $args['input_value'] ) {
        $selected = 'selected';      
      } else $selected = '';
 
      $options .= '<option value="' . $count . '"' . $selected . '>' . $count . '</option>';
 
   }
     
   $string = '<div class="quantity real"><span>Select Quantity</span><select id="realQty" name="' . $args['input_name'] . '">' . $options . '</select></div>';
 
   if ( $echo ) {
      echo $string;
   } else {
      return $string;
   }
  
}

add_action( 'woocommerce_review_order_before_submit', 'bbloomer_add_checkout_minimum_notice', 9 );
    
function bbloomer_add_checkout_minimum_notice() {
if ( class_exists( 'WC_Subscriptions_Product' ) && WC_Subscriptions_Cart::cart_contains_subscription( $product )) {
  woocommerce_form_field( 'sub_min_notice', array(
     'type'          => 'checkbox',
     'class'         => array('form-row sub_min_notice'),
     'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
     'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
     'required'      => true,
     'label'         => 'I acknowledge that a minimum of 3 subscription cycles is required for 40% off promotion.*',
  )); 
}
   
}

?>