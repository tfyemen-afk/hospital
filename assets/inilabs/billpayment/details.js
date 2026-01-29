jQuery(document).ready(function() {
  'use strict';
  let ghref = 1;
  jQuery(document).on('click','.ghref', function() {
    let href        = jQuery(this).attr('href');
    let querydata = 0;

    if(href === '#bill') {
      querydata = 1;
    } else if(href === '#billitem') {
      querydata = 2;
    } else if(href === '#payment') {
      querydata = 3;
    } else if(href === '#bill-summary') {
      querydata = 4;
    }

    ghref = querydata;
    let printpreviewurl = THEMEBASEURL+'billpayment/detailsprintpreview'+'/'+displayuhID+'/'+ghref;
    jQuery('.pdfurl').attr('href', printpreviewurl);
  });

  jQuery(document).on('click', '#sendpdf', function() {
    let to       = jQuery('#to').val();
    let subject  = jQuery('#subject').val();
    let message  = jQuery('#message').val();
    let printpreviewID  = ghref;
    let error    = 0;

    if(to === '' || to === null) {
      error++;
      jQuery('#to_error').html('The To field is required').css('text-align', 'left').css('color', 'red');
      jQuery('#to').addClass('is-invalid').parent().addClass('text-danger');
    } else {
      jQuery('#to_error').html('');
      jQuery('#to').removeClass('is-invalid').parent().removeClass('text-danger');
      if(check_email(to) === false) {
        error++
      }
    }

    if(subject === '' || subject === null) {
      error++;
      jQuery('#subject_error').html('The Subject field is required').css('text-align', 'left').css('color', 'red');
      jQuery('#subject').addClass('is-invalid').parent().addClass('text-danger');
    } else {
      jQuery('#subject_error').html('');
      jQuery('#subject').removeClass('is-invalid').parent().removeClass('text-danger');
    }

    if(error === 0) {
      jQuery('#sendpdf').attr('disabled','disabled');

      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'billpayment/detailssendmail',
        data: { 'to': to, 'subject': subject, 'message': message, 'displayuhID': displayuhID, 'printpreviewID': printpreviewID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          let response = JSON.parse(data);
          if (response.status === false) {
            jQuery('#sendpdf').removeAttr('disabled');
            jQuery.each(response, function(index, value) {
              if(index !== 'status') {
                toastr['error'](value);
                toastr.options = {
                  'closeButton': true,
                  'debug': false,
                  'newestOnTop': false,
                  'progressBar': false,
                  'positionClass': 'toast-top-right',
                  'preventDuplicates': false,
                  'onclick': null,
                  'showDuration': '500',
                  'hideDuration': '500',
                  'timeOut': '5000',
                  'extendedTimeOut': '1000',
                  'showEasing': 'swing',
                  'hideEasing': 'linear',
                  'showMethod': 'fadeIn',
                  'hideMethod': 'fadeOut'
                }
              }
            });
          } else {
            location.reload();
          }
        }
      });
    }
  });
});
