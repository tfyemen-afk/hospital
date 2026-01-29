jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery(document).on('change', '#roleID', function() {
    let roleID = jQuery(this).val();
    if(parseInt(roleID)) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'resetpassword/getuser',
        data: {'roleID' : roleID, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function(data) {
          let response = JSON.parse(data);
          if(response.status) {
            jQuery('#userID').html(response.data);
          } else {
            jQuery('#userID').html('<option value="0">'+resetpassword_please_select+'</option>');
            toastr.error(response.message);
          }
        }
      });
    }
  });
});