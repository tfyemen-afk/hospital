jQuery(document).ready(function() {
  'use strict';

  jQuery(document).on('click', '.on-off-switch-small-checkbox', function() {
    let status = '';
    let menulogid = '';
    if(jQuery(this).prop('checked')) {
      status = 'checked';
      menulogid = jQuery(this).parent().attr('id');
    } else {
      status = 'unchecked';
      menulogid = jQuery(this).parent().attr('id');
    }

    if((status !== '') && (menulogid !== '')) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'menulog/status',
        data: { 'menulogID': menulogid, 'status': status, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          if(data === 'Success') {
            toastr['success']('Success');
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
          } else {
            toastr['error']('Error');
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
    }
  });
});