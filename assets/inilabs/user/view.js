
jQuery(document).ready(function() {
  'use strict';
  jQuery('.userDIV').mCustomScrollbar({
    axis:'x'
  });

  jQuery(document).on('change', '.file-upload-input', function () {
    if(this.files.length > 0) {
      let file = this.files[0];
      jQuery('.label-text-hide').text(file.name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });

  jQuery(document).on('click', '.upload_document', function() {
    let formData = new FormData(jQuery('#formData')[0]);
    jQuery.ajax({
      dataType: 'json',
      type: 'POST',
      url: THEMEBASEURL+'user/uploaddocument',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function(data){
        let response = JSON.parse(data);
        if(response.status) {
          location.reload();
        } else {
          if(response.document_title) {
            jQuery('.document_title').addClass('is-invalid');
            jQuery('#error_document_title').html(response.document_title);
          } else {
            jQuery('.document_title').removeClass('is-invalid');
            jQuery('#error_document_title').html('');
          }

          if(response.document_file) {
            jQuery('.document_file').addClass('is-invalid');
            jQuery('#error_document_file').html(response.document_file);
          } else {
            jQuery('.document_file').removeClass('is-invalid');
            jQuery('#error_document_file').html();
          }
        }
      }
    });
  });

  jQuery(document).on('click', '#sendpdf', function() {
    let to       = jQuery('#to').val();
    let subject  = jQuery('#subject').val();
    let message  = jQuery('#message').val();
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
        url: THEMEBASEURL+'user/sendmail',
        data: {'to': to, 'subject': subject, 'message': message, 'userID': userID, [CSRFNAME] : CSRFHASH},
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




