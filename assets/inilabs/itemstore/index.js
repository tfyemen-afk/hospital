
  jQuery(document).on('click', '.viewModalBtn', function() {
    'use strict';
    let itemstoreID = jQuery(this).attr('id');
    if(itemstoreID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'itemstore/view',
        data: {'itemstoreID' : itemstoreID, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });