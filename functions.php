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
    /*wp_deregister_style( 'font-awesome-5' );*/
    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );
    wp_dequeue_script( 'wc-cart-fragments' ); 

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

function custom_loop_product_title_inject($product){
  global $product;
  $product_id = $product->get_parent_id();
  $product_main_title = get_post_meta( $product_id, '_bhww_main_title_wysiwyg', true );

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
    wp_enqueue_script( 'jquery' );
    wp_add_inline_script( 'jquery', '
     jQuery(document).ready(function(){

        addGeneratedRadioButtons();
        
      });
      jQuery("subscription-message").on("click"){
        addGeneratedRadioButtons();
      }
      function addGeneratedRadioButtons(){
        console.log("made it");
        jQuery( ".variations_form" ).on( "wc_variation_form woocommerce_update_variation_values", function() {
          jQuery( "div.generatedRadios" ).remove();
          jQuery( "table.variations select" ).each( function() {
            var selName = jQuery( this ).attr( "name" );
            jQuery( "select[name=" + selName + "] option" ).each( function() {
              var option = jQuery( this );
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
                jQuery( optionHtml ).click( function() {
                  select.val( value ).trigger( "change" );
                } )
              )
            } ).parent().hide();
          } );
          generateRadiosButtons();
        });

      };
 
    function generateRadiosButtons(){
     var selectedVariant = jQuery(".generatedRadios.selected label input").val();
     selectedVariant = selectedVariant.split(" ")[0];
     jQuery(".current_selected_variant").text(selectedVariant);
      jQuery(".input-text.qty").change(function(){
        var selectedVariant = jQuery(".generatedRadios.selected label input").val();
        var selectedQty = jQuery(this).children("option:selected").val();
      jQuery(".current_selected_variant").text(selectedVariant);
      jQuery(".quantity #realQty").val(selectedQty);
      jQuery(".delivery-every .current_selected_qty").text(selectedQty);
        if(selectedQty <= 1){
          selectedVariant = selectedVariant.split(" ")[0];
          jQuery(".current_selected_variant").text(selectedVariant);
      }else if(selectedQty>=2){
        if(selectedVariant == "Box"){
          jQuery(".current_selected_variant").text(selectedVariant + "es");
        }
        else{
          selectedVariant = selectedVariant.split(" ")[0];
          jQuery(".current_selected_variant").text(selectedVariant + "s");
        }
      }
    });
      var $savetext = jQuery(".subscription-price");
      
      
      
        // purchase options
    jQuery(".purchase-options input").change(function(){
      console.log(this);
      if (jQuery(this).val() == "one-time") updatePurchaseOptions(0);
      else updatePurchaseOptions(jQuery(".purchase-options select").val());

      jQuery(this).closest("li").addClass("selected");
      jQuery(this).parent("li").siblings().removeClass("selected");
      console.log("end hello");
    });

    jQuery(".wcsatt-options-wrapper .wcsatt-options-product input").change(function(){

      if (jQuery(this).val() == "one-time"){
        updatePurchaseOptions(0);
      }else{
        updatePurchaseOptions(jQuery(".wcsatt-options-wrapper .wcsatt-options-product select").val());
      }
      console.log("this is in wcsattwrapper function");
      jQuery(this).siblings(".purchase-options .selected .subscriptions-list .subscription-price").children("li").addClass("selected");
      jQuery(this).siblings(".purchase-options .selected .subscriptions-list .subscription-price").children("li").siblings().removeClass("selected");

    });



    jQuery(".purchase-options select").change(function(){
      jQuery(".purchase-options input[value=\'subscription\']").prop("checked", true).change();
    });

    function updatePurchaseOptions(v){
      jQuery(".wcsatt-options-product input[value=\'"+ v +"\']").prop("checked", true).change();
    }
    if( jQuery( ".variations_form select" ).length  ){

      // get json value from woocomerce from
        
      var product_attr    =   jQuery.parseJSON( jQuery(".variations_form").attr("data-product_variations") ),
          obj_attr    = "";

      
        jQuery( ".variations_form select" ).on( "change", function () {           
           // Create New Array by selecting variations
            jQuery( ".variations_form select" ).each(function( index ) {
                
                 obj_attr[ jQuery(this).attr("name") ] = jQuery(this).val();
                
            });
            
            // Get Variations
            jQuery.each( product_attr, function( index, loop_value ) {
            
                if( JSON.stringify( obj_attr ) === JSON.stringify( loop_value.attributes )  ){
                    jQuery(".one-time-price").html( loop_value.price_html );
                    
                }
            }); 
        });
    }
  };
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
  if ( has_term( 'broad-tincture-template', 'product_tag' ) ) {
    echo '<div class="bb-content">' . do_shortcode( '[fl_builder_insert_layout slug="broad-spectrum-tincture"]' ) . '</div>';
  }
  if ( has_term( 'water-soluble-tincture-template', 'product_tag' ) ) {
    echo '<div class="bb-content">' . do_shortcode( '[fl_builder_insert_layout slug="water-soluble-tincture"]' ) . '</div>';
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


//add_filter( 'woocommerce_account_menu_items', 'custom_remove_downloads_my_account', 999 );
 
function custom_remove_downloads_my_account( $items ) {
unset($items['downloads']);
return $items;
}

function iconic_add_to_cart_button_text( $text, $product ) {
  if ( ! $product->is_in_stock() ) {
        return '<div class="woocommerce-button" title="' . esc_attr__( 'Select options', 'woocommerce' ) . '">Sold Out</div>';
    }else{
    return '<div class="woocommerce-button" title="' . esc_attr__( 'Select options', 'woocommerce' ) . '">Add to Cart</div>';
  }
}

add_filter( 'iconic_wssv_add_to_cart_button_text', 'iconic_add_to_cart_button_text', 10, 2 );

add_action( 'woocommerce_after_shop_loop_item', 'misha_after_add_to_cart_btn' );
 
function misha_after_add_to_cart_btn(){
  global $product;
  echo '
   <a href="#" rel="nofollow" onClick="addGeneratedRadioButtons()" data-jckqvpid="' . $product->get_parent_id().':'. $product->get_id() . '" class="iconic-wqv-button iconic-wqv-button--align-center subscription-message"> Subscribe &amp; Save 25&percnt;</a>';
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



//add_filter( 'woocommerce_account_menu_items', 'bbloomer_remove_address_my_account', 1 );
 
function bbloomer_remove_address_my_account( $items ) {
   unset( $items['edit-address'] );
   return $items;
}
 
// -------------------------------
// 2. Second, print the ex tab content (woocommerce_account_edit_address) into an existing tab (woocommerce_account_edit-account_endpoint). See notes below!
//add_action( 'woocommerce_account_edit-account_endpoint', 'woocommerce_account_edit_address' );


function redirect_user() {

  if (isset($_SERVER['HTTP_REFERER']) && ! is_user_logged_in() && !is_woocommerce() && is_page('my-account') && !strstr( $referrer,'wp-admin' )) {
    $return_url = esc_url( home_url( '/dashboard-console' ) );
    wp_redirect( $return_url );
    die();
  }
}
add_action( 'template_redirect', 'redirect_user' );

// ------------------
// 1. Register new endpoint (URL) for My Account page
// Note: Re-save Permalinks or it will give 404 error
  
function bbloomer_add_wholesale_portal_endpoint() {
    add_rewrite_endpoint( 'wholesale-portal', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'bbloomer_add_wholesale_portal_endpoint' );
  
// ------------------
// 2. Add new query var
  
function bbloomer_wholesale_portal_query_vars( $vars ) {
    $vars[] = 'wholesale-portal';
    return $vars;
}
  
add_filter( 'query_vars', 'bbloomer_wholesale_portal_query_vars', 0 );
  
// ------------------
// 3. Insert the new endpoint into the My Account menu
  
function bbloomer_add_wholesale_portal_link_my_account( $items ) {
    $items['wholesale-portal'] = 'Wholesale Portal';
    return $items;
}
  
add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_wholesale_portal_link_my_account', 100, 1 );
  
// ------------------
// 4. Add content to the new tab
  
function bbloomer_wholesale_portal_content() {
   echo '<h3>Wholsale Portal</h3><p>Welcome to the wholesale portal. As a wholesale customer, you can select any number of cases from our product line and receiving keystone pricing when you order at least 1 case.</p>';
   echo do_shortcode( '[product_table]' );
}
  
add_action( 'woocommerce_account_wholesale-portal_endpoint', 'bbloomer_wholesale_portal_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format



function bbloomer_add_referral_link_endpoint() {
    add_rewrite_endpoint( 'refer-a-friend', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'bbloomer_add_referral_link_endpoint' );
  
// ------------------
// 2. Add new query var
  
function bbloomer_referral_link_query_vars( $vars ) {
    $vars[] = 'refer-a-friend';
    return $vars;
}
  
add_filter( 'query_vars', 'bbloomer_referral_link_query_vars', 0 );
  
// ------------------
// 3. Insert the new endpoint into the My Account menu
  
function bbloomer_add_referral_link_my_account( $items ) {
    $items['refer-a-friend'] = 'Refer a Friend';
    return $items;
}
  
add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_referral_link_my_account', 100, 1 );
  
// ------------------
// 4. Add content to the new tab
  
function bbloomer_referral_link_content() {
   echo '<h3>Refer a Friend</h3><p>Send your friends and family a $20 Coupon code when they purchase their first order on hennepens.com! When they complete their first order you will receive a coupon for $5.00 in store credit.</p>';
   echo '<div class="unique-code"><p>Your Unique Coupon Code</p><span class="coupon-code-box">' . do_shortcode( '[automatewoo_advocate_referral_coupon]' ) . '</span></div>';
  echo do_shortcode( '[automatewoo_referrals_page]' );

}
  
add_action( 'woocommerce_account_refer-a-friend_endpoint', 'bbloomer_referral_link_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format


function bbloomer_add_premium_support_link_my_account( $items ) {
// Remove the logout menu item.
$logout = $items['customer-logout'];
unset( $items['customer-logout'] );
 
// Insert your custom endpoint.
$items['wholesale-portal'] = 'Wholesale Portal';
$items['refer-a-friend'] = 'Refer a Friend';
 
// Insert back the logout item.
$items['customer-logout'] = $logout;
  
return $items;
}
   
add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_premium_support_link_my_account' );

function webroom_woocommerce_coupon_links(){

  // Bail if WooCommerce or sessions aren't available.

  if (!function_exists('WC') || !WC()->session) {
    return;
  }

  /**
   * Filter the coupon code query variable name.
   *
   * @since 1.0.0
   *
   * @param string $query_var Query variable name.
   */
  $query_var = apply_filters('woocommerce_coupon_links_query_var', 'coupon_code');

  // Bail if a coupon code isn't in the query string.

  if (empty($_GET[$query_var])) {
    return;
  }

  // Set a session cookie to persist the coupon in case the cart is empty.

  WC()->session->set_customer_session_cookie(true);

  // Apply the coupon to the cart if necessary.

  if (!WC()->cart->has_discount($_GET[$query_var])) {

    // WC_Cart::add_discount() sanitizes the coupon code.

    WC()->cart->add_discount($_GET[$query_var]);
  }
}
add_action('wp_loaded', 'webroom_woocommerce_coupon_links', 30);
add_action('woocommerce_add_to_cart', 'webroom_woocommerce_coupon_links');

add_filter( 'wc_payment_gateway_authorize_net_cim_activate_apple_pay', '__return_true' );


//////////////////////////////////////////////////////////////// Register cannabinoids Taxonomy
add_action( 'init', 'custom_product_cannabinoid_taxonomy', 0 );
function custom_product_cannabinoid_taxonomy()  {

  $labels = array(
    'name'                       => 'Cannabinoids',
    'singular_name'              => 'Cannabinoid (product_cannabinoid)',
    'menu_name'                  => 'Cannabinoids',
    'all_items'                  => 'All Cannabinoids',
    'parent_item'                => 'Parent Cannabinoid',
    'parent_item_colon'          => 'Parent Cannabinoid:',
    'new_item_name'              => 'New Cannabinoid Name',
    'add_new_item'               => 'Add New Cannabinoid',
    'edit_item'                  => 'Edit Cannabinoid',
    'update_item'                => 'Update Cannabinoid',
    'separate_items_with_commas' => 'Separate Cannabinoids with commas',
    'search_items'               => 'Search Cannabinoid',
    'add_or_remove_items'        => 'Add or remove Cannabinoid',
    'choose_from_most_used'      => 'Choose from the most used Cannabinoids',
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => false,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
  );
  register_taxonomy( 'product_cannabinoid', 'product', $args );

}


// Add custom multi-select fields in variation setting tab
add_action( 'woocommerce_product_after_variable_attributes', 'add_variation_settings_fields', 20, 3 );
function add_variation_settings_fields( $loop, $variation_data, $variation_post ) {

  // Get product certs
  $terms = get_terms([
    'taxonomy' => 'product_cannabinoid',
    'hide_empty' => false,
  ]);
  $options = []; // Initialize options array

  // Loop through each wp_term object and set term names in an array
  foreach ($terms as $term) {
    $term_name = __( $term->name, "woocommerce" );
    $options[$term_name] = $term_name;
  }

  woocommerce_wp_select( array(
    'id'            => '_product_cannabinoid'.$loop,
    'name'          => '_product_cannabinoid'.$loop.'[]',
    'label'         => __("Cannabinoids", "woocommerce" ),
    'options'       => $options,
    'class'         => 'product-cert-select',
    'custom_attributes' => array('multiple' => 'multiple'),
    'value'         => get_post_meta( $variation_post->ID, '_product_cannabinoid', true ),
  ), $variation_post->ID );

  echo '<div class="options_group">';

  woocommerce_wp_checkbox( array (
      'id'            => '_not_ready_to_sell_variation'.$loop,
      'label'         => __( '&nbsp;Call To Order', 'woocommerce' ),
      'description'   => __( '', 'woocommerce' ),
      'value'         => get_post_meta( $variation_post->ID, '_not_ready_to_sell_variation', true ),
    ), $variation_post->ID );

  echo '</div>';

  echo '
  <script>
  jQuery(document).ready(function( $ ) {
    $(".product-cert-select").select2();
    $(".select2-container").css({"width": "100%"});
  });
  </script>
  ';

}

// Save product certs & CTO for variations
add_action( 'woocommerce_save_product_variation', 'save_product_cannabinoid_variation_field', 11, 2 );
function save_product_cannabinoid_variation_field( $variation_id, $i ) {
  $post_data =  isset( $_POST['_product_cannabinoid'.$i] ) ? $_POST['_product_cannabinoid'.$i] : null;
  update_post_meta( $variation_id, '_product_cannabinoid', $post_data );
}
// CTO for variations
add_action( 'woocommerce_save_product_variation', 'save_cto_variation_field', 11, 2 );
function save_cto_variation_field( $variation_id, $i ) {
  $woocommerce_checkbox = isset( $_POST['_not_ready_to_sell_variation'.$i] ) ? 'yes' : null;
  update_post_meta( $variation_id, '_not_ready_to_sell_variation', $woocommerce_checkbox );
}

// Load product certs for variations (on frontend)
add_filter( 'woocommerce_available_variation', 'load_product_cannabinoid_variation_field' );
function load_product_cannabinoid_variation_field( $variations ) {
  $certsDivider = ' • ';
  $variations['product_cannabinoid'] = str_replace(',', $certsDivider, wc_get_formatted_variation(get_post_meta( $variations[ 'variation_id' ], '_product_cannabinoid', true ), true, false));
  return $variations;
}

// Load CTO Variations (on frontend)
add_filter( 'woocommerce_available_variation', 'load_cto_variation_field' );
function load_cto_variation_field( $variations ) {
  $variations['not_ready_to_sell_variation'] = get_post_meta( $variations[ 'variation_id' ], '_not_ready_to_sell_variation', true );
  return $variations;
}


////////////////////////////////////////////////////// cannabinoid TAGS FOR SIMPLE PRODUCTS
add_action( 'woocommerce_after_shop_loop_item_title', 'show_product_cannabinoids', 5 );
add_action( 'woocommerce_single_product_summary', 'show_product_cannabinoids', 11, 0 );
function show_product_cannabinoids() {

  global $product;

  $variation_id = $product->get_id();
  $current_certs = get_the_terms( get_the_ID(), 'product_cannabinoid' ); // GLOBAL PRODUCT CERTS
  $variation_certs = str_replace(', ', ' • ', wc_get_formatted_variation(get_post_meta( $variation_id , '_product_cannabinoid', true), true, false)); // VARIATION PRODUCT CERTS

  if ( $variation_certs ) {
    echo '<span class="text-success">' . $variation_certs . '</span>';
  } else {

    if ( $current_certs && ! is_wp_error( $current_certs ) ) {

      echo '<h6 class="text-center">Active Cannabinoids:</h6><div id="cert-tags-' . $variation_id . '" class="cert-tags">';

      foreach ($current_certs as $cert) {
        $cert_name = $cert->name;
        if ($cert_name) {
          echo '<span class="cert-tag ' . $cert_name . '">' . $cert_name . '</span>';
        }
      }

      echo '</div>';

    }
  }
}


?>