jQuery(document).ready(function() {
  'use strict';

  jQuery('.select2').select2();
  jQuery('.datepicker').datetimepicker({
    autoclose : true,
    format : 'dd-mm-yyyy HH:ii P',
    showMeridian : 'day',
    todayHighlight : true,
  }).datetimepicker('setDate', new Date());

  jQuery(document).on('change', '#ambulanceID', function() {
    let ambulanceID = jQuery(this).val();
    if(ambulanceID !== '0') {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'ambulancecall/get_driver_info',
        data: { 'ambulanceID' : ambulanceID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function (data) {
        let response = JSON.parse(data);
        if(response.status) {
          jQuery('#drivername').val(response.data.drivername);
        } else {
          jQuery('#drivername').val('');
        }
      }
    });
    } else {
      jQuery('#drivername').val('');
    }
  });

  jQuery(document).on('click', '.viewModalBtn', function() {
    let ambulancecallID = jQuery(this).attr('id');
    if(ambulancecallID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'ambulancecall/view',
        data: { 'ambulancecallID' : ambulancecallID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });

});