jQuery(document).on('click', '.going-event', function (e) {
  'use strict';
  e.preventDefault();
  let id = jQuery(this).attr('id');
  if (id) {
    jQuery.ajax({
      dataType : 'json',
      type : 'POST',
      url : THEMEBASEURL + 'frontend/eventGoing',
      data : {'id' : id, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        let response = jQuery.parseJSON(data);
        if (response.status === true) {
          toastr[ 'success' ](response.message);
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
          toastr[ 'error' ](response.message);
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