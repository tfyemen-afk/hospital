jQuery(document).on('click', '.viewModalBtn', function () {
  'use strict';
  let medicineManufacturerID = jQuery(this).attr('id');
  if (medicineManufacturerID > 0) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL+'medicinemanufacturer/view',
      data : { 'medicinemanufacturerID' : medicineManufacturerID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#viewModal .modal-body').html(data);
      }
    });
  }
});