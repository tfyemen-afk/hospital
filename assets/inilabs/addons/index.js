jQuery(document).ready(function() {
  'use strict';
  jQuery(document).on('change', '.file-upload-input', function () {
    if(this.files.length > 0) {
      let file = this.files[0];
      jQuery('.label-text-hide').text(file.name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });
});