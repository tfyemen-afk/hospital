jQuery(document).ready(function () {
  'use strict';
  jQuery('.select2').select2();
  jQuery('#date').datetimepicker({
    autoclose : true,
    format : 'dd-mm-yyyy HH:ii P',
    showMeridian : 'day',
    todayHighlight : true,
  });

  jQuery(document).on('change', '#categoryID', function () {
    let itemcategoryID = jQuery(this).val();
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'itemcheckin/get_item',
      data : {'itemcategoryID' : itemcategoryID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#itemID').html(data);
      }
    });
  });

  jQuery(document).on('click', '.viewModalBtn', function () {
    let itemcheckinID = jQuery(this).attr('id');
    if (itemcheckinID > 0) {
      jQuery.ajax({
        type : 'POST',
        url : THEMEBASEURL + 'itemcheckin/view',
        data : {'itemcheckinID' : itemcheckinID, [ CSRFNAME ] : CSRFHASH},
        dataType : 'html',
        success : function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });
});