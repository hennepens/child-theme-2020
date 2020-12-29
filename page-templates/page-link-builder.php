<?php
/**
 * Template Name: Link Builder EMAIL TESTING
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header('shop'); ?>

<?php
// Setup your custom query
$args = array( 'post_type' => 'product');
$loop = new WP_Query( $args );
?>

<div class="container">
<script>
  var slugify = function(text) {
    return text.toString().toLowerCase()
      .replace(/\s+/g, '')           // Replace spaces with -
      .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
      .replace(/\-\-+/g, '-')         // Replace multiple - with single -
      .replace(/^-+/, '')             // Trim - from start of text
      .replace(/-+$/, '');            // Trim - from end of text
  }

  jQuery(document).ready( function() {
    var queryArray = [];
    var fullstring = jQuery('.fullstring').text();
    jQuery('select.link-list').on('change', function(i, v) {
      self = jQuery(this);
      q = '';
      var optionSelected = jQuery("option:selected", this);
      jQuery('option').removeClass('selected');
      jQuery(optionSelected).toggleClass('selected','');
      
      jQuery('.variant-parent-id').text(optionSelected.attr('data-parent-id'));
      if(optionSelected.is(':selected')) {
          
          queryArray = jQuery.grep(queryArray, function(item) {
            return item.key !== optionSelected.attr('data-key');
          });
        
        queryArray.push({
          key: self.attr('data-key'),
          val: self.val(),
          parval: self.attr('data-parent-id')
        });
      } else {

        queryArray = jQuery.grep(queryArray, function(item) {
          return item.val !== self.val();
        });
      }
      if(queryArray.length) {
        jQuery('.variant-link-item-id').text( function() {
          
          jQuery.each( queryArray, function(i, v) {
            q = queryArray[i].val;
          });
          return q;
        });
      } else {
        jQuery('.variation-link-item-id').text('');
      }
      var fullstring = jQuery('.fullstring').text();
       jQuery('input.customlink').val(fullstring);
    });

    jQuery('select.link-qty').on('change', function() {
      self = jQuery(this);
      q = '';
      var optionSelected = jQuery("option:selected", this);
      if(optionSelected.is(':selected')) {
          queryArray = jQuery.grep(queryArray, function(item) {
            return item.key !== optionSelected.attr('data-key');
          });
        
        queryArray.push({
          key: self.attr('data-key'),
          val: self.val()
        });
        
      } else {
        queryArray = jQuery.grep(queryArray, function(item) {
          return item.val !== self.val();
        });
      }
      if(queryArray.length) {
        jQuery('.link-item-qty').text( function() {
          
          jQuery.each( queryArray, function(i, v) {
            if(queryArray[i].val == 1){
              q = '';
              }else{          
                q = '&quantity=' + queryArray[i].val;
              }
          });
          return q;
        });
      } else {
        jQuery('.link-item-qty').text('');
      }
      var fullstring = jQuery('.fullstring').text();
      jQuery('input.customlink').val(fullstring);
    });

    jQuery('select.link-subscribe-length').on('change', function() {
      self = jQuery(this);
      q = '';
      var optionSelected = jQuery("option:selected", this);
      if(optionSelected.is(':selected')) {
          queryArray = jQuery.grep(queryArray, function(item) {
            return item.key !== optionSelected.attr('data-key');
          });
        
        queryArray.push({
          key: self.attr('data-key'),
          val: self.val()
        });
        
      } else {
        queryArray = jQuery.grep(queryArray, function(item) {
          return item.val !== self.val();
        });
      }
      if(queryArray.length) {
        jQuery('.variant-link-item-subscribe').html( function() {
          
          jQuery.each( queryArray, function(i, v) {
            if(queryArray[i].val == 1){
              q = '';
              }else{ 
               parentID = jQuery('.link-list option:selected').attr('data-parent-id');
                q = '&convert_to_sub_<span class="variant-parent-id">' + parentID + '</span>=' + queryArray[i].val;
              }
          });
          return q;
        });
      } else {
        jQuery('.variant-link-item-subscribe').text('');
      }
      var fullstring = jQuery('.fullstring').text();
      jQuery('input.customlink').val(fullstring);
    });

    jQuery(".add-row").click(function(){
      var productline = jQuery("#product-line").val();
      var productlinename = jQuery("#product-line").find('option:selected').attr("name");
      var productlineattr = jQuery("#product-line").find('option:selected').attr("attributename");
      var productlineparentid = jQuery("#product-line").find('option:selected').attr("data-parent-id");
      var productlineid = jQuery("#product-line").val();
      var quantityline = jQuery("#quantity-line").val();
      var purchaseline = jQuery("#purchase-line").val();
      var purchaselinename = jQuery("#purchase-line").find('option:selected').attr("name");
      var markup = "<tr><td><input type='checkbox' name='record'></td><td>" + productlinename + "</td><td>"+ productlineattr +"<td>" + quantityline + "</td><td>" + purchaselinename + "</td><td>" + productlineid + "</td><td>" + productlineparentid + "</td></tr>";
      jQuery("table tbody").append(markup);
    });
        
    jQuery(".delete-row").click(function(){
      jQuery("table tbody").find('input[name="record"]').each(function(){
        if(jQuery(this).is(":checked")){
              jQuery(this).parents("tr").remove();
          }
      });
    });

  });
</script>
<h2>Select Products to add to link</h2>
<form>
  <select id="product-line" class="link-list">
      <option selected>Select a Product</option>
      <?php
      while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <?php  $variations = $product->get_available_variations();
         $parent_id = $product->get_id();
        foreach ( $variations as $key => $value ) { 
          $variation_id = $value['variation_id'];
         $variation_obj = new WC_Product_variation($variation_id);
         $stock = $variation_obj->get_stock_quantity(); ?>
         <?php foreach ($value['attributes'] as $attribute => $term_slug ) { ?>
          <option data-key="variation-option" value="<?php echo $value['variation_id']; ?>" name="<?php echo the_title() ?>" attributename="<?php echo $term_slug  ?>"  data-parent-id="<?php echo $parent_id; ?>" <?php if($stock == 0){?> disabled <?php }?>>
            <?php echo the_title() . " "; 
                    // Get the taxonomy slug
                    $taxonmomy = str_replace( 'attribute_', '', $attribute );

                    // Get the attribute label name
                    $attr_label_name = wc_attribute_label( $taxonmomy );

                    // Display attribute labe name
                    $term_name = get_term_by( 'slug', $term_slug, $taxonmomy )->name;

                    // Testing output
                    echo $attr_label_name . ': ' . $term_name;
                    if($stock == 0){
                      echo '- out of stock';
                    }
                ?>
          </option>
        <?php } ?>
        <?php } ?>
      <?php endwhile; wp_reset_query(); // Remember to reset ?>
  </select>
  <select id="quantity-line" class="link-qty">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
  </select>
  <select id="purchase-line" class="link-subscribe-length" >
      <option value="0" name="One-time Purchase">One-time Purchase</option>
      <option value="2_week" name="Every 2 Weeks">2 Weeks</option>
      <option value="3_week" name="Every 3 Weeks">3 Weeks</option>
      <option value="4_week" name="Every 4 Weeks">4 Weeks</option>
      <option value="5_week" name="Every 5 Weeks">5 Weeks</option>
      <option value="6_week" name="Every 6 Weeks">6 Weeks</option>
    </select>
      <input type="button" class="add-row" value="Add Order Line">
    </form>
<table width="100%" style="padding: 10px;margin: 30px 0;" class="link-builder-container">
   <thead style="border-bottom: 2px solid #000;">
      <tr>
          <th><input type='checkbox' name='record'></th>
          <th>Product</th>
          <th>Size</th>
          <th>Quantity</th>
          <th>Purchase Type</th>
          <th>Variant ID</th>
          <th>Parent ID</th>
      </tr>
    </thead>
    <tr class="link-builder-form-row">
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    </tr> 
</table>
 <button type="button" class="delete-row">Delete Order Line(s)</button>

<br/>
<?php $fullstring = "https://dev.hennepens.com/checkout/?add-to-cart=<span class='variant-link-item'><span class='variant-link-item-id'></span><span class='link-item-qty'></span><!--<span class='variant-link-item-additional'><span class='variant-link-item-id'></span><span class='link-item-qty'></span></span>--></span><span class='variant-link-item-subscribe'></span>";?>
<span class="fullstring" style="visibility: hidden;"><?php echo $fullstring; ?></span>
<form method="POST">
  <input type="text" name="customlink" placeholder="select products above" style="width: 100%;" class="customlink"  value="" />
  <input type="text" name="recipientname" placeholder="Name of Recipient" value="" /><br/>
  <input type="text" name="linkemail" placeholder="Email of Recipient" value="" /><br/>
  <textarea name="message" placeholder="Add a Custom Message" value="" ></textarea><br/>
  <button type="submit" name="submit" value="submit" method="post" >Send Now!</button>
</form>
</div>
<?php 
if(isset($_POST['submit'])){
function get_custom_email_html($heading = false, $mailer, $recipientname, $message, $customlink ) {



  $template = 'emails/prebuilt-cart.php';

  return wc_get_template_html( $template, array(
    'email_heading' => $heading,
    'sent_to_admin' => false,
    'plain_text'    => false,
    'email'         => $mailer,
    'recipientname' => $recipientname,
    'message'       => $message,
    'customlink'    => $customlink
  ) );

}

// load the mailer class
$mailer = WC()->mailer();

//format the email
$recipient = $_POST['linkemail'];
$subject = __("Your Checkout is Ready!", 'understrap');
$recipientname = $_POST['recipientname'];
$message = $_POST['message'];
$customlink = $_POST['customlink'];
$content = get_custom_email_html($subject, $mailer, $recipientname, $message, $customlink);
$headers = "Content-Type: text/html\r\n";

//send the email through wordpress
$mailer->send($recipient, $subject, $content, $headers );
}
?>
<?php get_footer(); ?>