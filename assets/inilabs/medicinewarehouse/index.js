jQuery(document).on('click', '.viewModalBtn', function() {
  'use strict';
    let medicinewarehouseID = jQuery(this).attr('id');
    if(medicinewarehouseID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'medicinewarehouse/view',
        data: { 'medicinewarehouseID' : medicinewarehouseID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });