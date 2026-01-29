jQuery(document).on('click', '.viewModalBtn', function () {
  'use strict';
  let tpaID = jQuery(this).attr('id');
  if (tpaID > 0) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'tpa/view',
      data : {'tpaID' : tpaID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#viewModal .modal-body').html(data);
      }
    });
  }
});