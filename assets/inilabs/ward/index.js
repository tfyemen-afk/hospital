jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();

  jQuery(document).on('click', '.viewModalBtn', function() {
    let wardID = jQuery(this).attr('id');
    if(wardID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'ward/view',
        data: { 'wardID' : wardID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });
});



