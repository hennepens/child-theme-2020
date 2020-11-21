<style>
/*
* Help Icon
*/

.help-icon{
	display: inline-block;
	vertical-align: middle;
	line-height: 20px;
	margin: 0 6px;

	-webkit-transform: translateY(-1px);
	transform: translateY(-1px);

	position: relative;
	z-index: 2;
}

.help-icon-inner{
	cursor: pointer;
	display: block;

	font-size: 20px;
	color: gray;
}

.help-icon-inner:hover{
	color: black;
}

.help-icon-inner::before{
	vertical-align: top;
}

.help-icon-hover{
	width: 272px;
	margin-top: 21px;
	display: none;

	box-shadow: 0 0 7px rgba(0,0,0,0.25);
	z-index: 2;

	-webkit-transform: translateX(-50%);
	transform: translateX(-50%);

	font-size: 12px;
	line-height: 19px;

	text-align: center;
	text-decoration: none;
	text-transform: none;

	position: absolute;
	top: 100%;
	left: 50%;
}

.help-icon:hover .help-icon-hover{
	display: block;
}

.help-icon-hover::before{
	width: 15px;
	height: 15px;
	margin-top: 1px;
	content: "";

	background-color: white;
	box-shadow: inherit;

	-webkit-transform: translate(-50%,50%) rotate(45deg);
	transform: translate(-50%,50%) rotate(45deg);

	position: absolute;
	bottom: 100%;
	left: 50%;
}

.help-icon-hover-inner{
	background-color: white;
	position: relative;
	display: block;
}

.help-icon-hover.covering-image .help-icon-hover-inner{
	padding: 5px;
}

.help-icon-hover.with-paddings .help-icon-hover-inner{
	padding: 20px 14px;
}


/*
* Product subscriptions
*/

.single-product .wcsatt-sub-options,
.single-product .wcsatt-sub-discount{
	font-size: 1.2rem;
	font-weight: 700;
	color: gray;
}

.single-product .wcsatt-sub-options{
	margin-left: 0.5rem;
}

.single-product .purchase-options{
	max-width: 460px;
	margin: 0 0 1.5rem;
	padding-left: 0;
}

.single-product .purchase-options li{
	min-height: 60px;
	margin-bottom: 0;
	padding: 10px;

	-webkit-align-items: center;
	align-items: center;

	display: -webkit-flex;
	display: flex;
}

.single-product .purchase-options li.selected{
	background-color: #edebe6;
}

.single-product .purchase-options li::before{
	display: none;
}

.single-product .purchase-options input{
	margin: 0 6px 0px 0;
	height: 1em;
	width: 1em;

	-webkit-tap-highlight-color: transparent;
	cursor: pointer;
}

.single-product .purchase-options label{
	margin-bottom: 0;

	-webkit-tap-highlight-color: transparent;
	cursor: pointer;
}

.single-product .purchase-options select{
	background-color: white;
	margin-left: 15px;
	padding: 4px 8px;
	height: 40px;
	width: 140px;
}

.single-product .purchase-options .help-icon{
	margin-left: 15px;
}
</style>

<script>
(function($){
    $(document).ready(function(){
        
        // purchase options
		$('.purchase-options input').change(function(){

			if ($(this).val() == 'one-time') updatePurchaseOptions(0);
			else updatePurchaseOptions($('.purchase-options select').val());

			$(this).closest('li').addClass('selected')
				.siblings().removeClass('selected');

		});

		$('.purchase-options select').change(function(){
			$('.purchase-options input[value="subscription"]').prop('checked', true).change();
		});

		function updatePurchaseOptions(v){
			$('.wcsatt-options-product input[value="'+ v +'"]').prop('checked', true).change();
		}
		
    });
})(jQuery);
</script>

<?php

// exit if accessed directly
if (!defined('ABSPATH')) exit;

$subscription_options = $hidden_options = array();

$tip_text = sprintf(__('We’ll ship your favorite %s products based on the schedule that you select. This way, you will never run out. You can change the schedule, pause, or cancel anytime.'), get_company_name());

foreach ($options as $option) {

	// visible controls
	if ($option['value'] == 0) {

		$one_time_option = "<li". ($option['selected'] ? " class='selected'" : "") .">".
			"<input type='radio' id='one-time-purchase' name='purchase-options' value='one-time'". ($option['selected'] ? " checked" : "") ." />".
			"<label for='one-time-purchase'>". __('One-Time Purchase') ."</label></li>\n";

	} else {

		if ($option['selected'])
			$selected_subscription_option = $option['selected'];

		$subscription_options[] = "<option value='". $option['value'] ."'". ($option['selected'] ? " selected" : "") .">".
			sprintf('%d %s', $option['data']['subscription_scheme']['interval'], ucfirst($option['data']['subscription_scheme']['period'])) ."</option>\n";
	}

	// hidden controls
	$hidden_options[] = sprintf('<li class="%1$s"><label><input type="radio" name="convert_to_sub_%2$d" data-custom_data="%3$s" value="%4$s" %5$s autocomplete="off" />'.
		'<span class="%1$s-details">%6$s</span></label></li>',
			esc_attr($option['class']),
			absint($product_id),
			esc_attr(json_encode($option['data'])),
			esc_attr($option['value']),
			checked($option['selected'], true, false),
			$option['description']
	);
}

echo
"<ul class='purchase-options'>\n".

	(isset($one_time_option) ? $one_time_option : "").

	($subscription_options ? "<li". (isset($selected_subscription_option) ? " class='selected'" : "") .">".
		"<input type='radio' id='subscriptions-list' name='purchase-options' value='subscription'". (isset($selected_subscription_option) ? " checked" : "") ." />\n".
		"<label for='subscriptions-list'>". ($prompt ? strip_tags($prompt) : __('Choose a subscription plan:')) ."</label>\n".
		"<select name='subscription-options'>". implode('', $subscription_options) ."</select>\n" : "").
		get_help_icon($tip_text) ."</li>\n".

"</ul>\n".

"<div class='wcsatt-options-wrapper' style='display: none;'>\n".

	"<ul class='wcsatt-options-product'>\n".
		implode('', $hidden_options).
	"</ul>\n".

"</div>\n";