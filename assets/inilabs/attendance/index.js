
jQuery(document).ready(function() {
  'use strict';
  jQuery('.datepicker').datepicker({
    autoclose : true,
    format : 'dd-mm-yyyy',
  });

  jQuery(document).on('click', '.saveattendance', function () {
    let attendances = {};
    jQuery('.attendance').each(function () {
      let name = jQuery(this).attr('name');
      let val = 'A';
      if (jQuery('input:radio[name=' + name + ']').is(':checked')) {
         val = jQuery('input:radio[name=' + name + ']:checked').val();
      }
      attendances[ name ] = val;
    });

    let date = jQuery('#date').val();
    if (date) {
      jQuery.ajax({
        type : 'POST',
        url : THEMEBASEURL+'attendance/save_attendance',
        data : {'date' : date, 'attendances' : attendances, [CSRFNAME] : CSRFHASH},
        dataType : 'html',
        success : function (data) {
          let response = JSON.parse(data);
          if (response.status) {
            window.location.href = THEMEBASEURL+'attendance/index';
          }
        }
      });
    }
  });
});