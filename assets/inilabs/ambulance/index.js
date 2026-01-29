jQuery(document).ready(function () {
  'use strict';

  jQuery('.select2').select2();
  jQuery(document).on('click', '.viewModalBtn', function() {
    let ambulanceID = jQuery(this).attr('id');
    if(ambulanceID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'ambulance/view',
        data: { 'ambulanceID' : ambulanceID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });
});