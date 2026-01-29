jQuery(document).ready(function () {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datepicker').datetimepicker({
    autoclose : true,
    format : 'dd-mm-yyyy HH:ii P',
    showMeridian : 'day',
    todayHighlight : true,
    startDate : date
  });

  jQuery('#username').keyup(function () {
    jQuery(this).val(jQuery(this).val().replace(/[^a-zA-Z0-9 ]/g, ''));
  });

  jQuery(document).on('change', '.file-upload-input', function () {
    if (this.files.length > 0) {
      jQuery('.label-text-hide').text(this.files[ 0 ].name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });

  jQuery(document).on('change', '#patienttypeID', function () {
    let patienttypeID = jQuery(this).val();
    if (patienttypeID === '0') {
      jQuery('#admissiondatediv').hide();
      jQuery('#creditlimitdiv').hide();
      jQuery('#warddiv').hide();
      jQuery('#beddiv').hide();

      jQuery('#appointmentdatediv').show();
      jQuery('#amountdiv').show();
      jQuery('#paymentstatusdiv').show();
      if (jQuery('#paymentstatus').val() === '1') {
        jQuery('#paymentmethoddiv').show();
      } else {
        jQuery('#paymentmethoddiv').hide();
      }
    } else {
      jQuery('#appointmentdatediv').hide();
      jQuery('#amountdiv').hide();
      jQuery('#paymentstatusdiv').hide();
      jQuery('#paymentmethoddiv').hide();

      jQuery('#admissiondatediv').show();
      jQuery('#creditlimitdiv').show();
      jQuery('#warddiv').show();
      jQuery('#beddiv').show();
    }
  });

  jQuery(document).on('change', '#paymentstatus', function () {
    let paymentstatus = jQuery(this).val();
    if (paymentstatus === '1') {
      jQuery('#paymentmethoddiv').show();
    } else {
      jQuery('#paymentmethoddiv').hide();
    }
  });

  jQuery(document).on('change', '#wardID', function () {
    let wardID = jQuery(this).val();
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'patient/bedcall',
      data : {'wardID' : wardID, bedID : myBedID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#bedID').html(data);
      }
    });
  });
  jQuery(document).on('change', '#doctorID', function() {
    let doctorID = jQuery(this).val();
    jQuery.ajax({
      url: THEMEBASEURL+'patient/getAmount',
      type: 'POST',
      dataType: 'html',
      data: { 'userID':doctorID, [CSRFNAME] : CSRFHASH },
      success: function (data) {
        jQuery('#amount').val(data);
      }
    });
  });

  let patienttypeID = myPatienttypeID;
  console.log(typeof patienttypeID);
  if (patienttypeID === 0) {

    jQuery('#admissiondatediv').hide();
    jQuery('#creditlimitdiv').hide();
    jQuery('#warddiv').hide();
    jQuery('#beddiv').hide();

    jQuery('#appointmentdatediv').show();
    jQuery('#amountdiv').show();
    jQuery('#paymentstatusdiv').show();
    if(jQuery('#paymentstatus').val() === '1') {
      jQuery('#paymentmethoddiv').show();
    } else {
      jQuery('#paymentmethoddiv').hide();
    }
  } else {
    jQuery('#appointmentdatediv').hide();
    jQuery('#amountdiv').hide();
    jQuery('#paymentstatusdiv').hide();
    jQuery('#paymentmethoddiv').hide();

    jQuery('#admissiondatediv').show();
    jQuery('#creditlimitdiv').show();
    jQuery('#warddiv').show();
    jQuery('#beddiv').show();
  }
});
