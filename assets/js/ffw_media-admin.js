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


  /** 
   * MEDIA_TYPE Conditionals
   */
  function media_type_conditionals(obj) {

    // vars
    var media_type  = $('#ffw_media_type'),
        options     = media_type.find('option'),
        options_arr = [];

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


  /* DOCUMENT READY
  ================================================== */
  $(document).ready(function (e){
      media_type_conditionals();
  });


})(jQuery);