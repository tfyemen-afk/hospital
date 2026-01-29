
  jQuery(document).on('click', '.viewModalBtn', function() {
    'use strict';
    let patientID = jQuery(this).attr('id');

    if(patientID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'bedmanager/view',
        data: {'patientID' : patientID, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });
