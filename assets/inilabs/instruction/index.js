jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery(document).on('click', '#searchuhID', function() {
    let displayuhID     = jQuery('#uhid').val();
    jQuery.ajax({
      type: 'POST',
      dataType: 'html',
      url: THEMEBASEURL+'instruction/get_instruction_url',
      data: { 'displayID' : displayID, 'displayuhID' : displayuhID, [CSRFNAME] : CSRFHASH },
      success: function (data) {
        window.location = data;
      },
    });
  });
});