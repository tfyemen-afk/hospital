
jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.daterange').daterangepicker({
    autoApply:true
  });

  jQuery(document).on('change', '#roleID', function() {
    let roleID = jQuery(this).val();
    if(roleID) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'leaveapply/getuser',
        data: {'roleID' : roleID, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function(data) {
          let response = JSON.parse(data);
          if(response.status) {
            jQuery('#applicationto_userID').html(response.data);
          } else {
            jQuery('#applicationto_userID').html('<option value="0">'+leaveapply_please_select+'</option>');
            toastr.error(response.message);
          }
        }
      });
    }
  });

  jQuery(document).on('change', '.file-upload-input', function () {
    if(this.files.length > 0) {
      jQuery('.label-text-hide').text(this.files.name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });
});