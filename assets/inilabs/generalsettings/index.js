jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery('#captcha_status').change(function() {
    let captcha_status = jQuery(this).val();
    if(captcha_status === '1') {
      jQuery('#recaptcha_site_key_id').show(300);
      jQuery('#recaptcha_secret_key_id').show(300);
    } else {
      jQuery('#recaptcha_site_key_id').hide(300);
      jQuery('#recaptcha_secret_key_id').hide(300);
    }
  });

  jQuery('.file-upload-input').change(function () {
    if(this.files.length > 0) {
      jQuery('.label-text-hide').text(this.files[0].name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });

  if(captchastatus === 1) {
    jQuery('#recaptcha_site_key_id').show(300);
    jQuery('#recaptcha_secret_key_id').show(300);
  } else {
    jQuery('#recaptcha_site_key_id').hide(300);
    jQuery('#recaptcha_secret_key_id').hide(300);
  }
});