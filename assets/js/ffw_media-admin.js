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
  function media_type_conditionals(method) {

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


      // change event handler for media type select box
      media_type.change(function (event) {
        
        // set vars
        var selection = $(this).find('option:selected').val();

        // handle logic for selections [1 : youtube 2 : vimeo 3 : flickr]
        for (var i=0;i<options_arr.length;i++) {
          if ( selection == options_arr[i] ) {
            console.log(options_arr[i], 'selected');

            $('#ffw_media_types').children().hide();
            $('#'+options_arr[i]+'-selected')
              .show()
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
      $('#'+selected+'-selected')
        .show()

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