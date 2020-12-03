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
    if ( is_singular() && is_product() ) {
      wp_enqueue_script( 'custom-schedule-options', get_stylesheet_directory_uri() . '/js/custom-schedule-options.js', array(), $the_theme->get( 'Version' ), true );
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
remove_filter( 'woocommerce_cart_item_subtotal', array( 'WC_Subscriptions_Switcher', 'add_cart_item_switch_direction' ), 10, 3 );
add_action( 'wp_enqueue_scripts', function() {
  if( ! function_exists( 'is_product' ) || ! is_product() ) { return; }
  wp_enqueue_script( 'jquery' );
  wp_add_inline_script( 'jquery', '
    jQuery( document ).ready( function( $ ) {
      var qtyUpdate = $(".input-text.qty").val();
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
            console.log("Help: "+ isSelected);
            if(isSelected.includes("checked")){
              var selectedClass = "selected"
               $(".current_selected_variant").text(value);
              if(value == "Case (10 Boxes)" && qtyUpdate <= 1){
                $(".current_selected_variant").text("Case");
              }
              else if(value == "Case (10 Boxes)" && qtyUpdate >= 2){
                 $(".current_selected_variant").text("Cases");
              }
              else if(value == "Box" && qtyUpdate <= 1){
                 $(".current_selected_variant").text("Box");
              }
              else if(value == "Box" && qtyUpdate >= 2){
                 $(".current_selected_variant").text("Boxes");
              }

              else if(value == "Jar" && qtyUpdate <= 1){
                 $(".current_selected_variant").text("Jar");
              }
              else if(value == "Jar" && qtyUpdate >= 2){
                 $(".current_selected_variant").text("Jars");
              }

              else if(value == "Bottle" && qtyUpdate <= 1){
                 $(".current_selected_variant").text("Bottle");
              }
              else if(value == "Bottles" && qtyUpdate >= 2){
                 $(".current_selected_variant").text("Bottles");
              }

              else if(value == "Case (10 Boxes)" && qtyUpdate <= 1){
                $(".current_selected_variant").text("Case");
              }
              else if(value == "Case (10 Boxes)" && qtyUpdate >= 2){
                 $(".current_selected_variant").text("Cases");
              }

              else if(value == "Case (6 Jars)" && qtyUpdate <= 1){
                $(".current_selected_variant").text("Case");
              }
              else if(value == "Case (6 Jars)" && qtyUpdate >= 2){
                 $(".current_selected_variant").text("Cases");
              }

              else if(value == "Case (4 Bottles)" && qtyUpdate <= 1){
                $(".current_selected_variant").text("Case");
              }
              else if(value == "Case (4 Bottles)" && qtyUpdate >= 2){
                 $(".current_selected_variant").text("Cases");
              }

            }
            else{
              var selectedClass=""
              
            };

            var radioHtml = `<input type="radio" name="${selName}" value="${value}" ${isSelected}>`;
            var optionHtml = `<div class="generatedRadios ${selectedClass}"><label>${radioHtml} ${label}</label></div>`;
           
            select.parent().append(
              $( optionHtml ).click( function() {
                var qtyUpdate = $(".input-text.qty").val();
                select.val( value ).trigger( "change" ); 
                
                if(value == "Case (10 Boxes)" && qtyUpdate <= 1){
                  $(".current_selected_variant").text("Case");
                }
                else if(value == "Case (10 Boxes)" && qtyUpdate >= 2){
                  $(".current_selected_variant").text("Cases");
                }
                else if(value == "Box" && qtyUpdate <= 1){
                  $(".current_selected_variant").text("Box");
                }
                else if(value == "Box" && qtyUpdate >= 2){
                  $(".current_selected_variant").text("Boxes");
                }

                else if(value == "Jar" && qtyUpdate <= 1){
                  $(".current_selected_variant").text("Jar");
                }
                else if(value == "Jar" && qtyUpdate >= 2){
                  $(".current_selected_variant").text("Jars");
                }

                else if(value == "Bottle" && qtyUpdate <= 1){
                  $(".current_selected_variant").text("Bottle");
                }
                else if(value == "Bottle" && qtyUpdate >= 2){
                  $(".current_selected_variant").text("Bottles");
                }

                else if(value == "Case (6 Jars)" && qtyUpdate <= 1){
                  $(".current_selected_variant").text("Case");
                }
                else if(value == "Case (6 Jars)" && qtyUpdate >= 2){
                  $(".current_selected_variant").text("Cases");
                }

                 else if(value == "Case (4 Bottles)" && qtyUpdate <= 1){
                  $(".current_selected_variant").text("Case");
                }
                else if(value == "Case (4 Bottles)" && qtyUpdate >= 2){
                  $(".current_selected_variant").text("Cases");
                }

              } )
            )
          } ).parent().hide();
        } );
         $(".input-text.qty").on("change", function(){
              var qtyUpdate = $(".input-text.qty").val();
              var variationSelect = $(".current_selected_variant").text();
              
              if(qtyUpdate <= 1 && (variationSelect == "Box" || variationSelect == "Boxes" )){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Boxes", "Box");

              }
              else if(qtyUpdate >= 2 && (variationSelect == "Box" || variationSelect == "Box")){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Box", "Boxes");

              }

              else if(qtyUpdate <= 1 && (variationSelect == "Jar" || variationSelect == "Jars" )){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Jars", "Jar");

              }
              else if(qtyUpdate >= 2 && (variationSelect == "Jar" || variationSelect == "Jar")){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Jar", "Jars");

              }

              else if(qtyUpdate <= 1 && (variationSelect == "Bottle" || variationSelect == "Bottles" )){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Bottles", "Bottle");

              }
              else if(qtyUpdate >= 2 && (variationSelect == "Bottle" || variationSelect == "Bottle")){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Bottle", "Bottle");

              }


              else if(qtyUpdate <= 1 && (variationSelect == "Case (10 Boxes)" || variationSelect == "Cases" )){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Cases", "Case");

              }
              else if(qtyUpdate >= 2 && (variationSelect == "Case (10 Boxes)" || variationSelect == "Case" )){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Case", "Cases");

              }

              else if(qtyUpdate <= 1 && (variationSelect == "Case (6 Jars)" || variationSelect == "Cases" )){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Cases", "Case");

              }
              else if(qtyUpdate >= 2 && (variationSelect == "Case (6 Jars)" || variationSelect == "Case" )){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Case", "Cases");

              }
              else if(qtyUpdate <= 1 && (variationSelect == "Case (4 Bottles)" || variationSelect == "Cases" )){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Cases", "Case");

              }
              else if(qtyUpdate >= 2 && (variationSelect == "Case (4 Bottles)" || variationSelect == "Case" )){
                
                var selectionPlural = $(".current_selected_variant").text().replace("Case", "Cases");

              }



              else{

              }
              $(".current_selected_qty").text(qtyUpdate);
              $(".current_selected_variant").text(selectionPlural);

            });
      } );
    } );
  ', 'after' );
} );

add_filter('woocommerce_reset_variations_link', '__return_empty_string');

function wc_subscriptions_custom_price_string( $pricestring ) {
    $pricestring = str_replace( 'every 2 months', 'Bi-Monthly', $pricestring );
    $pricestring = str_replace( 'month', 'Monthly', $pricestring );

    return $pricestring;
}
//add_filter( 'woocommerce_subscriptions_product_price_string', 'wc_subscriptions_custom_price_string' );
//add_filter( 'woocommerce_subscription_price_string', 'wc_subscriptions_custom_price_string' );

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

             console.log(jsonData); 
             console.log(regular_price); 

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

//add_filter( 'wcsatt_price_html_suffix', 'apfs_remove_suffix', 10, 3 );

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

?>