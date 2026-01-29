jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();

  jQuery(document).on('change', '.roleID', function () {
    let roleID = jQuery(this).val();
    let url = '';
    if (parseInt(roleID)) {
      url = THEMEBASEURL + 'makepayment/index/' + roleID;
    } else {
      url = THEMEBASEURL + 'makepayment/index';
    }
    window.location.href = url;
  });


});
