!(function ($){

  // GLOBAL VARS
  FFW_MEDIA = {
    debug     : true,
    elements  : {
      media_type  : $('#ffw_media_type')
    }
  }
  // OUTPUT GLOBAL OBJ IF DEBUGGING
  if ( FFW_MEDIA.debug ) console.log('ffw_media-admin.js DEBUG enabled. FFW_MEDIA Object: ', FFW_MEDIA);



  /* MEDIA_TYPE Conditionals 
  ================================================== */
  function media_type_conditionals( method ) {

    // vars
    var media_type  = $('#ffw_media_type'),
        options     = media_type.find('option'),
        options_arr = [];

    // if object doesnt exist, back out
    if ( !media_type.length || media_type.length != 1 ) { return false; }

    /////////////////////////////////
    // METHOD : 'change'
    /////////////////////////////////
    if ( method == 'change' ) {

      // create array of option values
      options.each(function (i, opt){
        if ( opt.value != 0 ) { options_arr[i] = opt.value; }
      });

      // DEBUG - log created options array
      if ( FFW_MEDIA.debug ) console.log(options_arr);

      function compare_vals(value_string) {
        if ( media_type_url_val.indexOf(value_string) > 0 && options_arr[i].indexOf(value_string) > 0 ) {
          return true;
        } else {
          return false;
        }
      }

      // set original value as data element    
      $('#ffw_media_type_url').data('orig-val', $('#ffw_media_type_url').val());

      // change event handler for media type select box
      media_type.change(function (event) {
        
        // set vars
        var selection = $(this).find('option:selected').val();
            
        // handle logic for selections [1 : youtube 2 : vimeo 3 : flickr]
        for (var i=0;i<options_arr.length;i++) {
          if ( selection == options_arr[i] ) {
            // debug logging
            if ( FFW_MEDIA.debug ) console.log(options_arr[i], 'selected');

            // set vars
            var media_types_wrap    = $('#ffw_media_types'),
                selected_option     = $('#'+options_arr[i]+'-selected'),
                selected_opt_val    = options_arr[i].slice(10),
                media_type_url      = $('#ffw_media_type_url'),
                media_type_url_val  = media_type_url.val(),
                media_type_url_orig = media_type_url.data('orig-val');
                    
            // hide all child elements
            media_types_wrap.children().hide();

            // empty the field if changed to different, based on stored origin data val
            if ( media_type_url_val.indexOf(selected_opt_val) <= 0 ) {
              media_type_url.val('');
            } 
            if ( media_type_url_orig.indexOf(selected_opt_val) > 0 ) {
              media_type_url.val(media_type_url_orig);
              if ( FFW_MEDIA.debug ) console.log('Restored original value: ', media_type_url_orig);
            }
          
            // show the selected option's subfields
            selected_option.show()
            
            // append the input url field to the select option subfield area
            media_type_url.show().appendTo(selected_option);

            // if wp gallery is selected, no need to show the url field
            if ( options_arr[i] === 'ffw_media_wp_gallery' ) {
              media_type_url.hide();
            }
          }
        }
      });
    }

    /////////////////////////////////
    // METHOD : 'onload'
    /////////////////////////////////
    if ( method == 'onload' ) {

      // set vars
      var selected = media_type.find('option:selected').val();

      // otherwise show the expanded options for the selected media type
      $('#ffw_media_types').children().hide();
      $('#'+selected+'-selected').show();
      $('#ffw_media_type_url').show();
      console.log('METHOD - "onload" ran successfully. Selected media_type option is: ', '"' + selected + '"');
    }
  }


  /* DOCUMENT READY
  ================================================== */
  $(document).ready(function (e){
      media_type_conditionals('onload');
      media_type_conditionals('change');
  });


})(jQuery);