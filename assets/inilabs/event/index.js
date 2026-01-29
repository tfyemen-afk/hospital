
jQuery(document).ready(function() {
  'use strict';
  jQuery('#date').daterangepicker({
    autoApply:true,
    locale: {
      format: 'DD/MM/YYYY hh:mm A',
    },
    timePicker:true
  });

  jQuery(document).on('change', '.file-upload-input', function () {
    if(this.files.length > 0) {
      jQuery('.label-text-hide').text(this.files[0].name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });
});