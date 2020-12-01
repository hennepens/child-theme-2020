jQuery(function ($) {

  /*
   * Initializes Autoship Radio Options
   */
  var autoship_as_radio_options = function(){

    /**
     * Adjust the Values based on the option
     */
    var triggerAutoshipChange = function(e){
      var $thisProduct = window.autoshipTemplateData.isCartPage ?
      $( e.target ).closest( window.autoshipTemplateData.cartItemCls ):
      $( e.target ).closest( window.autoshipTemplateData.productCls );

      var $autoshipOptions          = $thisProduct.find( window.autoshipTemplateData.optionsCls );
      var $autoshipYesRadio         = $autoshipOptions.find( window.autoshipTemplateData.yesBtn );
      var $autoshipNoRadio          = $autoshipOptions.find( window.autoshipTemplateData.noBtn );
      var $autoshipFrequencySelect  = $autoshipOptions.find( window.autoshipTemplateData.frequencySelectCls );

      // Is the user clicking on a Schedule Option or One-Time Purchase
      if ( $(this).val() ){
        $autoshipYesRadio.attr('checked', true).trigger('click');

        $autoshipFrequencySelect.val( $(this).val() ).trigger('change');
      } else {
        $autoshipNoRadio.attr('checked', true).trigger('click');
      }

    }

    /**
     * Catch the clicks on the radio options
     */
    $('.product')
    .find( '.autoship-options.autoship-radio-option' )
    .on( 'click', triggerAutoshipChange );

  }();

});
 