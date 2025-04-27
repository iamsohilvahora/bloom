jQuery(document).on('ready', function(){
  jQuery('#ce-toggle').click(function(event) {
     jQuery('.plan-toggle-wrap').toggleClass('active');
  });

  jQuery('#ce-toggle').change(function(){
    if(jQuery(this).is(':checked')) {
      jQuery('.tab-content #monthly').hide();
      jQuery('.monthly').hide();
      jQuery('.tab-content #yearly').show();
      jQuery('.yearly').show();
    }
    else{
      jQuery('.tab-content #monthly').show();
      jQuery('.monthly').show();
      jQuery('.tab-content #yearly').hide();
      jQuery('.yearly').hide();
    }
  });
});  