jQuery(document).on('click', '.viewModalBtn', function () {
  'use strict';
  let itemcategoryID = jQuery(this).attr('id');
  if (itemcategoryID > 0) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'itemcategory/view',
      data : {'itemcategoryID' : itemcategoryID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#viewModal .modal-body').html(data);
      }
    });
  }
});