jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();

  jQuery(document).on('change', '#medicinecategoryID', function() {
    let medicinecategoryID = jQuery(this).val();
    if(parseInt(medicinecategoryID)) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'damageandexpire/get_medicine',
        data: { 'medicinecategoryID': medicinecategoryID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          jQuery('#medicineID').html(data);
        }
      });
    }
  });

  jQuery(document).on('change', '#medicineID', function() {
    let medicineID         = jQuery(this).val();
    if(parseInt(medicineID)) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'damageandexpire/get_medicine_batch',
      data: { 'medicineID': medicineID, [CSRFNAME] : CSRFHASH },
      dataType: 'html',
        success: function(data) {
        jQuery('#batchID').html(data);
      }
    });
    }
  });

  jQuery(document).on('click', '.viewModalBtn', function() {
    let damageandexpireID = jQuery(this).attr('id');
    if(parseInt(damageandexpireID)) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'damageandexpire/view',
        data: { 'damageandexpireID': damageandexpireID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });
});