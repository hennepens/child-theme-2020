jQuery('.subscription-message2').on('click', function(e) { 

    e.preventDefault();

});

jQuery(document).ready(function($) {

    jQuery('.subscription-message2').on('click', function(e){ 

    e.preventDefault();

    $thisbutton = $(this),

                id = $thisbutton.val(),

                product_qty = $(this).attr('data-quantity') || 1,

                product_id = $(this).attr('data-product_id') || id,

                variation_id = $(this).attr('data-variation_id') || 0;
    var data = {

            action: 'ql_woocommerce_ajax_add_to_cart',

            product_id: product_id,

            product_sku: '',

            quantity: product_qty,

            variation_id: variation_id,

        };

    jQuery.ajax({

            type: 'post',

            url: wc_add_to_cart_params.ajax_url,

            data: data,

            beforeSend: function (response) {

                $thisbutton.removeClass('added').addClass('loading');

            },

            complete: function (response) {

                $thisbutton.addClass('added').removeClass('loading');

            }, 

            success: function (response) { 

                if (response.error & response.product_url) {

                    window.location = response.product_url;

                    return;

                } else { 

                    jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);

                } 

            }, 

        }); 

     }); 

});