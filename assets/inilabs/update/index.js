jQuery(document).on('change', '.file-upload-input', function () {
  'use strict';
  if (this.files.length > 0) {
    jQuery('.label-text-hide').text(this.files[ 0 ].name);
  } else {
    jQuery('.label-text-hide').text('Choose file');
  }
});

jQuery(document).on('click', '.getloginfobtn', function() {
  'use strict';
  let updateID =  jQuery(this).attr('id');
  if(updateID > 0) {
    jQuery.ajax({
      type: 'POST',
      url: THEMEBASEURL+'update/getloginfo',
      data: {'updateID' : updateID, [CSRFNAME] : CSRFHASH},
      dataType: 'html',
      success: function(data) {
        jQuery('#logcontent').html(data);
      }
    });
  }
});
