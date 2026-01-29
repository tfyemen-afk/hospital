jQuery(document).ready(function() {
  'use strict';

  let counterLength = document.querySelector('#send-email-message');
  document.getElementById('counter__length').innerHTML = counterLength.getAttribute('maxlength');
  function val(e) {
    let attrLength = counterLength.getAttribute('maxlength');
    let currentLength = counterLength.value.length;
    let totalLength = attrLength - currentLength;
    document.querySelector('#counter__length').innerHTML = totalLength;
    if (e.keyCode === 8) {
      document.querySelector('#counter__length').innerHTML = totalLength++;
    }
  }
  counterLength.addEventListener('keyup', val, false);

  function check_email(email) {
    let status = false;
    let emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
    if (email.search(emailRegEx) === -1) {
      jQuery('#to_error').html('Give a valid email').css('text-align', 'left').css('color', 'red');
    } else {
      status = true;
    }
    return status;
  }


  jQuery(document).on('click', '#send-email', function() {
    let error       = 0;
    let name        = jQuery('#send-email-name').val();
    let email       = jQuery('#send-email-email').val();
    let subject     = jQuery('#send-email-subject').val();
    let message     = jQuery('#send-email-message').val();


    if(name === '') {
      error++;
      jQuery('#send-email-name').css('border-color', 'red');
    } else {
      jQuery('#send-email-name').css('border-color', '');
    }

    if(email === '') {
      error++;
      jQuery('#send-email-email').css('border-color', 'red');
    } else {
      jQuery('#send-email-email').css('border-color', '');
    }

    if(subject === '') {
      error++;
      jQuery('#send-email-subject').css('border-color', 'red');
    } else {
      jQuery('#send-email-subject').css('border-color', '');
    }

    if(message === '') {
      error++;
      jQuery('#send-email-message').css('border-color', 'red');
    } else {
      jQuery('#send-email-message').css('border-color', '');
    }

    if(check_email(email) === false) {
      error++;
      jQuery('#send-email-email').css('border-color', 'red');
    } else {
      jQuery('#send-email-email').css('border-color', '');
    }

    if(error <= 0) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'frontend/contactMailSend',
        data: {'name' : name, 'email' : email, 'subject' : subject, 'message' : message, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function(data) {
          location.reload();
        }
      });
    }
  });
});
