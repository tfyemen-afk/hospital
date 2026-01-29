jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery(document).on('click', '.viewModalBtn', function() {
    let medicineID = jQuery(this).attr('id');
    if(medicineID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'medicine/view',
        data: { 'medicineID' : medicineID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  })
});