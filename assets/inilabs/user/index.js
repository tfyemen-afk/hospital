jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();

  jQuery('#dob').datepicker({
    autoclose   : true,
    startView   : 2,
    format      : 'dd-mm-yyyy',
  });

  jQuery('#jod').datepicker({
    autoclose       : true,
    format          : 'dd-mm-yyyy',
    todayHighlight  : true,
  });

  jQuery(document).on('change', '.file-upload-input', function () {
    if(this.files.length > 0) {
      let file = this.files[0];
      jQuery('.label-text-hide').text(file.name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });

  jQuery(document).ready(function () {
    let designationID = jQuery('#designationID').val();
    if(designationID == 3) {
      jQuery('.doctorExtra').show('slow');
    } else {
      jQuery('.doctorExtra').hide('slow');
    }
  });

  jQuery(document).on('change', '#designationID', function () {
    let designationID = jQuery(this).val();
    if(designationID == 3) {
      jQuery('.doctorExtra').show('slow');
    } else {
      jQuery('.doctorExtra').hide('slow');
    }
  });

});