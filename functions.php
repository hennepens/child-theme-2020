<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


//Page Slug Body Class
function add_slug_body_class( $classes ) {
global $post;
if ( isset( $post ) ) {
$classes[] = $post->post_type . '-' . $post->post_name;
}
return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Share Links', 'Post Type General Name', 'understap' ),
        'singular_name'       => _x( 'Share Link', 'Post Type Singular Name', 'understrap' ),
        'menu_name'           => __( 'Share Links', 'twentytwenty' ),
        'parent_item_colon'   => __( 'Parent Share Link', 'understrap' ),
        'all_items'           => __( 'All Share Links', 'understrap' ),
        'view_item'           => __( 'View Share Link', 'understrap' ),
        'add_new_item'        => __( 'Add New Share Link', 'understrap' ),
        'add_new'             => __( 'Add New', 'understrap' ),
        'edit_item'           => __( 'Edit Share Link', 'understrap' ),
        'update_item'         => __( 'Update Share Link', 'understrap' ),
        'search_items'        => __( 'Search Share Link', 'understrap' ),
        'not_found'           => __( 'Not Found', 'understrap' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'understrap' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Share Links', 'understrap' ),
        'description'         => __( 'Links shared to people', 'understrap' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'share categories' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'sharelinks', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );


function understrap_remove_scripts() {
  if( !current_user_can('administrator') ) {
    
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );
    wp_deregister_style( 'font-awesome-5' );
    wp_deregister_style( 'dashicons' ); 
    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );
    wp_dequeue_script( 'wc-cart-fragments' ); 

  }
  function unload_all_jquery() {
    //wp_enqueue_script("jquery");
    $jquery_ui = array(
        "jquery-ui-widget",
        "jquery-ui-mouse",
        "jquery-ui-accordion",
        "jquery-ui-autocomplete",
        "jquery-ui-slider",
        "jquery-ui-tabs",   
        "jquery-ui-draggable",
        "jquery-ui-droppable",
        "jquery-ui-selectable",
        "jquery-ui-position",
        "jquery-ui-datepicker",
        "jquery-ui-resizable",
        "jquery-ui-dialog",
        "jquery-ui-button"
    );

    foreach($jquery_ui as $script){
        wp_deregister_script($script);
    }
  }



    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', function(){
    wp_dequeue_style( 'xoo-wsc-fonts' );
  }, 999 );

add_action( 'wp_print_styles', 'tn_dequeue_font_awesome_style',100 );
function tn_dequeue_font_awesome_style() {
      wp_dequeue_style( 'fontawesome' );
      wp_deregister_style( 'fontawesome' );
      wp_dequeue_style( 'font-awesome' );
      wp_deregister_style( 'font-awesome' );
      wp_dequeue_style( 'font-awesome-5' );
      wp_deregister_style( 'font-awesome-5' );
}


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


remove_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title',10);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
add_action('woocommerce_shop_loop_item_title','custom_loop_product_title_inject',10);
add_action('woocommerce_single_product_summary','custom_single_product_title_inject',5);

add_filter( 'jetpack_sharing_counts', '__return_false', 99 );
add_filter( 'jetpack_implode_frontend_css', '__return_false', 99 );

function custom_single_product_title_inject(){
    global $post;
    $product_main_title = get_post_meta( $post->ID, '_bhww_main_title_wysiwyg', true );

    if ( ! empty( $product_main_title ) ) {
        echo '<h1 class="product_title entry-title">' . $product_main_title . '</h1>';
    }else{
      echo wp_kses_post(get_the_title());
    }
}

function custom_loop_product_title_inject(){
  global $product;
  $product_main_title = get_post_meta( $product->id, '_bhww_main_title_wysiwyg', true );

    if ( !empty( $product_main_title ) ) {
        echo '<h2 class="woocommerce-loop-product__title">' . $product_main_title . '</h2>';
    }else{
      echo wp_kses_post(get_the_title());
    }
}


function add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



remove_action( 'wp_footer', 'woocommerce_demo_store' );
add_action( 'wp_body_open', 'woocommerce_demo_store' );





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
  echo '<a href="#" rel="nofollow" data-product_id="'. $product->get_parent_id() .'" data-product_sku="'. $product->get_sku() .'" data-quantity="1" class="subscription-message button add_to_cart add_to_cart_button jck_wssv_add_to_cart" data-variation_id="'. $product->get_id() .'" data-convert_to_sub_'. $product->get_parent_id() .'="6_week"><div class="woocommerce-button" title="Select options">Subscribe &amp; Save 25&percnt;</div></a>

    <div class="popup">


    </div>';
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
       'label'         => 'I acknowledge that a minimum of 3 subscription cycles is required for 25% off promotion.*',
    )); 
  }
   
}

function webroom_add_multiple_products_to_cart( $url = false ) {
  // Make sure WC is installed, and add-to-cart qauery arg exists, and contains at least one comma.
  if ( ! class_exists( 'WC_Form_Handler' ) || empty( $_REQUEST['add-to-cart'] ) || false === strpos( $_REQUEST['add-to-cart'], ',' ) ) {
    return;
  }

  // Remove WooCommerce's hook, as it's useless (doesn't handle multiple products).
  remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );

  $product_ids = explode( ',', $_REQUEST['add-to-cart'] );
  $count       = count( $product_ids );
  $number      = 0;

  foreach ( $product_ids as $id_and_quantity ) {
    // Check for quantities defined in curie notation (<product_id>:<product_quantity>)
    
    $id_and_quantity = explode( ':', $id_and_quantity );
    $product_id = $id_and_quantity[0];

    $_REQUEST['quantity'] = ! empty( $id_and_quantity[1] ) ? absint( $id_and_quantity[1] ) : 1;

    if ( ++$number === $count ) {
      // Ok, final item, let's send it back to woocommerce's add_to_cart_action method for handling.
      $_REQUEST['add-to-cart'] = $product_id;

      return WC_Form_Handler::add_to_cart_action( $url );
    }

    $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
    $was_added_to_cart = false;
    $adding_to_cart    = wc_get_product( $product_id );

    if ( ! $adding_to_cart ) {
      continue;
    }

    $add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->get_type(), $adding_to_cart );

    // Variable product handling
    if ( 'variable' === $add_to_cart_handler ) {
      woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_variable', $product_id );

    // Grouped Products
    } elseif ( 'grouped' === $add_to_cart_handler ) {
      woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_grouped', $product_id );

    // Custom Handler
    } elseif ( has_action( 'woocommerce_add_to_cart_handler_' . $add_to_cart_handler ) ){
      do_action( 'woocommerce_add_to_cart_handler_' . $add_to_cart_handler, $url );

    // Simple Products
    } else {
      woo_hack_invoke_private_method( 'WC_Form_Handler', 'add_to_cart_handler_simple', $product_id );
    }
  }
}

// Fire before the WC_Form_Handler::add_to_cart_action callback.
add_action( 'wp_loaded', 'webroom_add_multiple_products_to_cart', 15 );


/**
 * Invoke class private method
 *
 * @since   0.1.0
 *
 * @param   string $class_name
 * @param   string $methodName
 *
 * @return  mixed
 */
function woo_hack_invoke_private_method( $class_name, $methodName ) {
  if ( version_compare( phpversion(), '5.3', '<' ) ) {
    throw new Exception( 'PHP version does not support ReflectionClass::setAccessible()', __LINE__ );
  }

  $args = func_get_args();
  unset( $args[0], $args[1] );
  $reflection = new ReflectionClass( $class_name );
  $method = $reflection->getMethod( $methodName );
  $method->setAccessible( true );

  //$args = array_merge( array( $class_name ), $args );
  $args = array_merge( array( $reflection ), $args );
  return call_user_func_array( array( $method, 'invoke' ), $args );
}

//Remove JQuery migrate
 
function remove_jquery_migrate( $scripts ) {
   if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
    if ( $script->deps ) { 
// Check whether the script has any dependencies

        $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
    }
  }
 }
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );

add_shortcode ('woo_featured_products', 'woo_featured_products' );
/**
 * Create WooCommerce Image Loop of Featured Products
 * @link https://wordpress.stackexchange.com/questions/195425/display-featured-products-through-custom-loop-in-woocommerce-on-template-page
 */
function woo_featured_products() {
ob_start();

    $meta_query  = WC()->query->get_meta_query();
    $tax_query   = WC()->query->get_tax_query();
    
    $tax_query[] = array(
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => 'featured',
        'operator' => 'IN',
    );

    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => -1,
        'orderby'             => 'date',
        'order'               => 'ASC',
        'meta_query'          => $meta_query,
        'tax_query'           => $tax_query,
    );
    $loop = new WP_Query( $args );

    while ( $loop->have_posts() ) : $loop->the_post(); 
    ?>
    
    <div> 
        <a href="<?php echo get_permalink( $loop->post->ID ) ?>">
            <?php the_post_thumbnail('large'); ?>
        </a>
    </div>

    <?php 
    endwhile; 

    wp_reset_query();     

return ob_get_clean();
}

## ---- 1. Backend ---- ##

// Adding a custom Meta container to admin products pages
add_action( 'add_meta_boxes', 'create_custom_meta_box' );
if ( ! function_exists( 'create_custom_meta_box' ) )
{
    function create_custom_meta_box()
    {
        add_meta_box(
            'custom_product_meta_box',
            __( 'Additional Product Information <em>(optional)</em>', 'cmb' ),
            'add_custom_content_meta_box',
            'product',
            'normal',
            'default'
        );
    }
}

//  Custom metabox content in admin product pages
if ( ! function_exists( 'add_custom_content_meta_box' ) ){
    function add_custom_content_meta_box( $post ){
        $prefix = '_bhww_'; // global $prefix;

        $main_title = get_post_meta($post->ID, $prefix.'main_title_wysiwyg', true) ? get_post_meta($post->ID, $prefix.'main_title_wysiwyg', true) : '';
        $args['textarea_rows'] = 6;

        echo '<p>'.__( 'Product Custom Title', 'cmb' ).'</p>';
        wp_editor( $main_title, 'main_title_wysiwyg', $args );

        echo '<input type="hidden" name="custom_product_field_nonce" value="' . wp_create_nonce() . '">';
    }
}



//Save the data of the Meta field
add_action( 'save_post', 'save_custom_content_meta_box', 10, 1 );
if ( ! function_exists( 'save_custom_content_meta_box' ) )
{

    function save_custom_content_meta_box( $post_id ) {
        $prefix = '_bhww_'; // global $prefix;

        // We need to verify this with the proper authorization (security stuff).

        // Check if our nonce is set.
        if ( ! isset( $_POST[ 'custom_product_field_nonce' ] ) ) {
            return $post_id;
        }
        $nonce = $_REQUEST[ 'custom_product_field_nonce' ];

        //Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'product' == $_POST[ 'post_type' ] ){
            if ( ! current_user_can( 'edit_product', $post_id ) )
                return $post_id;
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }

        // Sanitize user input and update the meta field in the database.
        update_post_meta( $post_id, $prefix.'main_title_wysiwyg', wp_kses_post($_POST[ 'main_title_wysiwyg' ]) );
    }
}

?>