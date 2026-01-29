

jQuery(document).on('click', '#sendpdf', function() {
  'use strict';
  let to       = jQuery('#to').val();
  let subject  = jQuery('#subject').val();
  let message  = jQuery('#message').val();
  let eventID  = myEventID;
  let error = 0;

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
      url: THEMEBASEURL+'event/sendmail',
      data: {'to': to, 'subject': subject, 'message': message, 'eventID': eventID, [CSRFNAME] : CSRFHASH},
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

jQuery(document).on('click', '#going', function() {
  'use strict';
  jQuery.ajax({
    type: 'POST',
    url: THEMEBASEURL+'event/eventcounter',
    data: {'status': 1, 'eventID': myEventID, [CSRFNAME] : CSRFHASH},
    dataType: 'html',
    success: function(data) {
      let response = JSON.parse(data);
      if(response.status) {
        location.reload();
      } else {
        toastr['error'](response.message);
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
    }
  });
});

jQuery(document).on('click', '#ignore', function() {
  'use strict';
  jQuery.ajax({
    type: 'POST',
    url: THEMEBASEURL+'event/eventcounter',
    data: {'status': 0, 'eventID': myEventID, [CSRFNAME] : CSRFHASH},
    dataType: 'html',
    success: function(data) {
      let response = JSON.parse(data);
      if(response.status) {
        location.reload();
      } else {
        toastr['error'](response.message);
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
    }
  });
});