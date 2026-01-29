jQuery(document).on('click', '.viewModalBtn', function () {
  'use strict';
  let itemsupplierID = jQuery(this).attr('id');
  if (itemsupplierID > 0) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'itemsupplier/view',
      data : {'itemsupplierID' : itemsupplierID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#viewModal .modal-body').html(data);
      }
    });
  }
});