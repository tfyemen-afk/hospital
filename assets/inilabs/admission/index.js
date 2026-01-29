
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

  jQuery(document).on('change', '#wardID', function() {
    let wardID = jQuery(this).val();
    if(wardID > 0) {
      jQuery.ajax({
        url: THEMEBASEURL+'admission/getbed',
        type: 'POST',
        dataType: 'html',
        data: { wardID: wardID, [CSRFNAME] : CSRFHASH },
        success: function (data) {
          jQuery('#bedID').html(data);
        }
      });
    }
  });

  jQuery(document).on('change', '#displayDoctorID', function() {
    let displayDoctorID   = jQuery(this).val();
    if(displayType === 'index') {
      window.location = THEMEBASEURL+'admission/index/'+strDisplayDate+'/'+displayDoctorID;
    } else {
      window.location = THEMEBASEURL+'admission/edit/'+admissionID+'/'+strDisplayDate+'/'+displayDoctorID;
    }
  });

  function dategenerate()
  {
    let today = new Date();
    let dd = today.getDate();
    let mm = today.getMonth()+1;
    let yyyy = today.getFullYear();

    if(dd<10) {
      dd = '0'+dd
    }

    if(mm<10) {
      mm = '0'+mm
    }

    today = dd+'-'+mm+'-'+yyyy;
    return today;
  }

  jQuery('#displayDate').datepicker({
    autoclose : true,
    format: 'dd-mm-yyyy',
    todayHighlight : true,
  });

  jQuery(document).on('change', '#displayDate', function() {
    let displayDate       = jQuery(this).val();
    let displayDoctorID   = jQuery('#displayDoctorID').val();
    let date = jQuery(this).val();
    if(displayDate === '') {
      date = dategenerate;
    }

    if(typeof displayDoctorID === 'undefined') {
      displayDoctorID = loginuserID;
    }

    jQuery.post(THEMEBASEURL+'admission/strtotimegenerate', { date: date, [CSRFNAME] : CSRFHASH }, function(strtotimegenerate) {
      if(displayType === 'index') {
        window.location = THEMEBASEURL+'admission/index/'+strtotimegenerate+'/'+displayDoctorID;
      } else {
        window.location = THEMEBASEURL+'admission/edit/'+admissionID+'/'+strtotimegenerate+'/'+displayDoctorID;
      }
    });
  });

});