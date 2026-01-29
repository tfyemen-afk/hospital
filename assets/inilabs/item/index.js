jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery(document).on('click', '.viewModalBtn', function() {
    let itemID = jQuery(this).attr('id');
    if(itemID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'item/view',
        data: {'itemID' : itemID, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });
});