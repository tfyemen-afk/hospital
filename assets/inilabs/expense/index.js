jQuery(document).ready(function () {
  'use strict';
  jQuery('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
  });

  jQuery('.file-upload-input').change(function () {
    if(this.files.length > 0) {
      jQuery('.label-text-hide').text(this.files[0].name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });
});
