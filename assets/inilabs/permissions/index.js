
jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery(document).on('change', '#roleID', function() {
    let roleID = jQuery(this).val();
    if(roleID === 0) {
      jQuery(this).addClass('is-invalid');
      jQuery(this).parent().parent().addClass('text-danger');
      toastr.error(permissions_please_select_role);
    } else {
      jQuery(this).removeClass('is-invalid');
      jQuery(this).parent().parent().removeClass('text-danger');
      roleID = jQuery.trim(roleID);
      let redirectURL = THEMEBASEURL+'permissions/index/'+roleID;
      window.location.href= redirectURL;
    }
  });

  jQuery('.mainmodule').each(function() {
    let mainmodule  = jQuery(this).attr('id');
    let mainidadd    = mainmodule+'_add';
    let mainidedit   = mainmodule+'_edit';
    let mainiddelete = mainmodule+'_delete';
    let mainidview   = mainmodule+'_view';

    if (!jQuery('#'+mainmodule).is(':checked')) {
      jQuery('#'+mainidadd).prop('disabled', true);
      jQuery('#'+mainidadd).prop('checked', false);

      jQuery('#'+mainidedit).prop('disabled', true);
      jQuery('#'+mainidedit).prop('checked', false);

      jQuery('#'+mainiddelete).prop('disabled', true);
      jQuery('#'+mainiddelete).prop('checked', false);

      jQuery('#'+mainidview).prop('disabled', true);
      jQuery('#'+mainidview).prop('checked', false);
    }
  });
});

function processCheck(event) {
  'use strict';
  let mainmodule  = jQuery(event).attr('id');

  let mainidadd    = mainmodule+'_add';
  let mainidedit   = mainmodule+'_edit';
  let mainiddelete = mainmodule+'_delete';
  let mainidview   = mainmodule+'_view';

  if(jQuery('#'+mainmodule).is(':checked')) {
    jQuery('#'+mainidadd).prop('disabled', false);
    jQuery('#'+mainidadd).prop('checked', true);

    jQuery('#'+mainidedit).prop('disabled', false);
    jQuery('#'+mainidedit).prop('checked', true);

    jQuery('#'+mainiddelete).prop('disabled', false);
    jQuery('#'+mainiddelete).prop('checked', true);

    jQuery('#'+mainidview).prop('disabled', false);
    jQuery('#'+mainidview).prop('checked', true);
  } else {
    jQuery('#'+mainidadd).prop('disabled', true);
    jQuery('#'+mainidadd).prop('checked', false);

    jQuery('#'+mainidedit).prop('disabled', true);
    jQuery('#'+mainidedit).prop('checked', false);

    jQuery('#'+mainiddelete).prop('disabled', true);
    jQuery('#'+mainiddelete).prop('checked', false);

    jQuery('#'+mainidview).prop('disabled', true);
    jQuery('#'+mainidview).prop('checked', false);
  }
}