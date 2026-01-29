jQuery(document).ready(function () {
  'use strict';
  jQuery('.select2').select2();
  jQuery(document).on('click', '#searchuhID', function () {
    let displaytypeID = jQuery('#patienttypeID').val();
    let displayuhID = jQuery('#uhid').val();
    jQuery.ajax({
      type : 'POST',
      dataType : 'html',
      url : THEMEBASEURL + 'prescription/get_patientinfo',
      data : {
        'displayID' : displayID,
        'displaytypeID' : displaytypeID,
        'displayuhID' : displayuhID,
        [ CSRFNAME ] : CSRFHASH
      },
      success : function (data) {
        window.location = data;
      },
    });
  });

  jQuery(document).on('change', '#patienttypeID', function () {
    let patienttypeID = jQuery(this).val();
    jQuery.ajax({
      type : 'POST',
      dataType : 'html',
      url : THEMEBASEURL + 'prescription/get_patient',
      data : {'patienttypeID' : patienttypeID, [ CSRFNAME ] : CSRFHASH},
      success : function (data) {
        jQuery('#uhid').html(data);
      },
    });
  });
});