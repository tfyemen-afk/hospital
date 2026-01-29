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
});

jQuery(document).ready(function() {
  'use strict';
  if(paymentstatus === 1) {
    jQuery('#paymentmethodDiv').show();
  } else {
    jQuery('#paymentmethodDiv').hide();
  }
});


jQuery(document).on('change', '#paymentstatus', function() {
  'use strict';
  let editpaymentstatus = jQuery(this).val();
  if(editpaymentstatus === '1') {
    jQuery('#paymentmethodDiv').show();
  } else {
    jQuery('#paymentmethodDiv').hide();
  }
});

jQuery(document).on('change', '#displayDoctorID', function() {
  'use strict';
  let displayDoctorID = jQuery(this).val();
  if(displayType === 'index') {
    window.location = THEMEBASEURL+'appointment/index/'+strDisplayDate+'/'+displayDoctorID;
  } else {
    window.location = THEMEBASEURL+'appointment/edit/'+appointmentID+'/'+strDisplayDate+'/'+displayDoctorID;
  }
});

function dategenerate()
{
  'use strict';
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

jQuery(document).ready(function() {
  'use strict';
  jQuery('#displayDate').datepicker({
    autoclose : true,
    format: 'dd-mm-yyyy',
    todayHighlight : true,
  });

  jQuery(document).on('change', '#departmentID', function() {
    let departmentID = jQuery(this).val();
    jQuery.ajax({
      url: THEMEBASEURL+'appointment/getdoctor',
      type: 'POST',
      dataType: 'html',
      data: { departmentID: departmentID, [CSRFNAME] : CSRFHASH },
      success: function (data) {
        jQuery('#doctorID').html(data);
      }
    });
  });

  jQuery(document).on('change', '#doctorID', function() {
    let doctorID = jQuery(this).val();
    jQuery('#type').val(0);
    if(doctorID > 0) {
      jQuery.ajax({
        url: THEMEBASEURL+'appointment/gettype',
        type: 'POST',
        dataType: 'html',
        data: { 'doctorID': doctorID, [CSRFNAME] : CSRFHASH },
        success: function (data) {
          jQuery('#type').html(data);
        }
      });
    }
  });

  jQuery(document).on('change', '#type', function() {
    let type = jQuery(this).val();
    let doctorID = jQuery('#doctorID').val();
    if(type > 0 && doctorID > 0) {
      jQuery.ajax({
        url: THEMEBASEURL+'appointment/getAmount',
        type: 'POST',
        dataType: 'html',
        data: { 'type': type, 'userID':doctorID, [CSRFNAME] : CSRFHASH },
        success: function (data) {
          jQuery('#amount').val(data);
        }
      });
    }
  });


  jQuery(document).on('change', '#displayDate', function() {
    let displayDate       = jQuery(this).val();
    let displayDoctorID   = jQuery('#displayDoctorID').val();
    let date;
    if(displayDate === '') {
      date = dategenerate;
    } else {
      date = jQuery(this).val();
    }

    if(typeof displayDoctorID === 'undefined') {
      displayDoctorID = loginuserID;
    }

    jQuery.post(THEMEBASEURL+'appointment/strtotimegenerate', { date: date, [CSRFNAME] : CSRFHASH }, function(strtotimegenerate) {
      if(displayType === 'index') {
        window.location = THEMEBASEURL+'appointment/index/'+strtotimegenerate+'/'+displayDoctorID;
      } else {
        window.location = THEMEBASEURL+'appointment/edit/'+appointmentID+'/'+strtotimegenerate+'/'+displayDoctorID;
      }
    });
  });
});


