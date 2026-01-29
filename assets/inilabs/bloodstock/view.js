jQuery(document).ready(function() {
  'use strict';

  let globalBloodBagID = 0;
  let globalBloodGroupID = 0;
  jQuery(document).on('click', '.viewModalBtn', function() {
    let bloodbagID = jQuery(this).attr('id');
    globalBloodBagID = bloodbagID;
    if(bloodbagID > 0) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'bloodstock/getbloodbaginfo',
        data: { 'bloodbagID' : bloodbagID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function (data) {
          let response = JSON.parse(data);
          if(response.status) {
            globalBloodGroupID = response.data['bloodgroupID'];
            let patientID = response.data['patientID'];

            let status = 0;
            if (response.data['status'] === '0') {
              status = 1;
            } else {
              status = 2;
            }

            jQuery('#status').val(status).select2();
            jQuery('#patientID').val(patientID).select2();
          }
        }
      });
    }
  });

  jQuery(document).on('click', '.updatebloodstock', function() {
    let bloodstock = {
      'status'      : jQuery('#status').val(),
      'patientID'   : jQuery('#patientID').val(),
      'bloodbagid'  : globalBloodBagID,
      'bloodgroupID': globalBloodGroupID,
      [CSRFNAME] : CSRFHASH
    };

    jQuery.ajax({
      type: 'POST',
      url: THEMEBASEURL+'bloodstock/bloodstockupdate',
      data: bloodstock,
      dataType: 'html',
      success: function(data) {
        let response = JSON.parse(data);
        if(response.statuss) {
          location.reload();
        } else {
          if(response.status) {
            jQuery('#status').addClass('is-invalid');
            jQuery('#error_status').html(response.status);
          } else {
            jQuery('#status').removeClass('is-invalid');
            jQuery('#error_status').html('');
          }

          if(response.patientID) {
            jQuery('#patientID').addClass('is-invalid');
            jQuery('#error_patientID').html(response.patientID);
          } else {
            jQuery('#patientID').removeClass('is-invalid');
            jQuery('#error_patientID').html();
          }
        }
      }
    });
  });

});
