jQuery(document).ready(function () {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datetimepicker').datetimepicker({
    autoclose : true,
    format : 'dd-mm-yyyy HH:ii P',
    showMeridian : 'day',
    todayHighlight : true,
  });

  jQuery(document).on('click', '.viewModalBtn', function () {
    let itemcheckoutID = jQuery(this).attr('id');
    if (itemcheckoutID > 0) {
      jQuery.ajax({
        type : 'POST',
        url : THEMEBASEURL + 'itemcheckout/view',
        data : {'itemcheckoutID' : itemcheckoutID, [ CSRFNAME ] : CSRFHASH},
        dataType : 'html',
        success : function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });

  jQuery('#categoryID').change(function () {
    let itemcategoryID = jQuery(this).val();
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'itemcheckout/get_item',
      data : {'itemcategoryID' : itemcategoryID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#itemID').html(data);
      }
    });
  });

  let setitemcategoryID = categoryID;
  let setitemID = itemID;
  if (setitemcategoryID > 0) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'itemcheckout/get_item',
      data : {'itemcategoryID' : setitemcategoryID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#itemID').html(data);
        jQuery('#itemID').val(setitemID);
      }
    });
  }
});