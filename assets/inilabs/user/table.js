jQuery(document).on('click', '.on-off-switch-small-checkbox', function () {
  'use strict';
  let status = '';
  let user_id = '';
  if (jQuery(this).prop('checked')) {
    status = 'checked';
    user_id = jQuery(this).parent().attr('id');
  } else {
    status = 'unchecked';
    user_id = jQuery(this).parent().attr('id');
  }

  if ((status !== '' || status !== null) && (user_id !== '')) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'user/status',
      data : {'userid' : user_id, 'status' : status, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        if (data === 'Success') {
          toastr[ 'success' ]('Success');
          toastr.options = {
            'closeButton' : true,
            'debug' : false,
            'newestOnTop' : false,
            'progressBar' : false,
            'positionClass' : 'toast-top-right',
            'preventDuplicates' : false,
            'onclick' : null,
            'showDuration' : '500',
            'hideDuration' : '500',
            'timeOut' : '5000',
            'extendedTimeOut' : '1000',
            'showEasing' : 'swing',
            'hideEasing' : 'linear',
            'showMethod' : 'fadeIn',
            'hideMethod' : 'fadeOut'
          }
        } else {
          toastr[ 'error' ]('Error');
          toastr.options = {
            'closeButton' : true,
            'debug' : false,
            'newestOnTop' : false,
            'progressBar' : false,
            'positionClass' : 'toast-top-right',
            'preventDuplicates' : false,
            'onclick' : null,
            'showDuration' : '500',
            'hideDuration' : '500',
            'timeOut' : '5000',
            'extendedTimeOut' : '1000',
            'showEasing' : 'swing',
            'hideEasing' : 'linear',
            'showMethod' : 'fadeIn',
            'hideMethod' : 'fadeOut'
          }
        }
      }
    });
  }
});