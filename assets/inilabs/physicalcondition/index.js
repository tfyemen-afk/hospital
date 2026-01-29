jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2' ).select2();
  jQuery('.datepicker').datetimepicker({
    autoclose : true,
    format : 'dd-mm-yyyy HH:ii P',
    showMeridian : 'day',
    todayHighlight : true,
    startDate : startDate
  });

  jQuery(document).on('change', '#patienttypeID', function() {
    let patienttypeID = jQuery('#patienttypeID').val();
    if(patienttypeID === '1') {
      jQuery('.dateDiv').show('slow');
    } else {
      jQuery('.dateDiv').hide('slow');
    }

    jQuery.ajax({
      url: THEMEBASEURL+'physicalcondition/getpatient',
      type: 'POST',
      dataType: 'html',
      data: { 'patienttypeID': patienttypeID, [CSRFNAME] : CSRFHASH },
      success: function(data) {
        jQuery('#uhid').html(data);
      }
    });
  });

  jQuery(document).on('click', '.viewModalBtn', function() {
    let heightweightbpID = jQuery(this).attr('id');
    if(heightweightbpID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'physicalcondition/view',
        data: { 'heightweightbpID' : heightweightbpID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });
});

jQuery(document).ready(function() {
  'use strict';
  let setpatienttypeID = patienttypeID;
  if(setpatienttypeID === 1) {
    jQuery('.dateDiv').show();
  } else if(setpatienttypeID === 0) {
    jQuery('.dateDiv').hide();
  } else {
    jQuery('.dateDiv').hide();
  }
});