jQuery(document).ready(function() {
  'use strict';

  jQuery('.select2').select2();
  jQuery('.mainsmtpDIV').hide();

  let set_email_engine = my_set_email_engine;
  if(set_email_engine === 'smtp') {
    jQuery('.mainsmtpDIV').show();
  } else if(set_email_engine === 'sendmail') {
    jQuery('.mainsmtpDIV').hide();
  } else if(set_email_engine === 'select') {
    jQuery('.mainsmtpDIV').hide();
  } else {
    if(email_engine === 'smtp') {
      jQuery('.mainsmtpDIV').show();
    }
  }

  jQuery(document).on('change', '#email_engine', function() {
    let get_email_engine = jQuery(this).val();
    if(get_email_engine === 'smtp') {
      jQuery('.mainsmtpDIV').show('slow');
    } else {
      jQuery('.mainsmtpDIV').hide('slow');
    }
  });
});