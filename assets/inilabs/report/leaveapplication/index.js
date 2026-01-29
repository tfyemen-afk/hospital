jQuery(document).ready(function () {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datepicker').datepicker({
    format : 'dd-mm-yyyy',
    autoclose : true
  });

  jQuery(function () {
    jQuery('#roleID').val(0);
    jQuery('#userID').val(0);
    jQuery('#categoryID').val(0);
    jQuery('#statusID').val(0);
  });

  jQuery(document).on('change', '#roleID', function () {
    jQuery('#load_leaveapplicationreport').html('');
    jQuery('#userDiv').show('slow');
    let roleID = jQuery(this).val();

    if (roleID > 0) {
      jQuery.ajax({
        type : 'POST',
        url : THEMEBASEURL + 'leaveapplicationreport/get_user',
        data : {'roleID' : roleID, [ CSRFNAME ] : CSRFHASH},
        success : function (data) {
          jQuery('#userID').html(data);
        }
      });
    }
  });

  jQuery(document).on('change', '#userID, #from_date, #to_date', function () {
    jQuery('#load_leaveapplicationreport').html('');
  });

  jQuery(document).on('click', '#get_leaveapplicationreport', function () {
    let roleID = jQuery('#roleID').val();
    let userID = jQuery('#userID').val();
    let categoryID = jQuery('#categoryID').val();
    let statusID = jQuery('#statusID').val();
    let from_date = jQuery('#from_date').val();
    let to_date = jQuery('#to_date').val();
    let error = 0;
    let field = {
      'roleID' : roleID,
      'userID' : userID,
      'categoryID' : categoryID,
      'statusID' : statusID,
      'from_date' : from_date,
      'to_date' : to_date,
      [ CSRFNAME ] : CSRFHASH
    };

    if (error === 0) {
      makingPostDataPreviousofAjaxCall(field);
    }
  });

  function makingPostDataPreviousofAjaxCall(field) {
    let passData = field;
    ajaxCall(passData);
  }

  function ajaxCall(passData) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'leaveapplicationreport/getleaveapplicationreport',
      data : passData,
      dataType : 'html',
      success : function (data) {
        let response = JSON.parse(data);
        renderLoder(response, passData);
      }
    });
  }

  function renderLoder(response, passData) {
    if (response.status) {
      jQuery('#load_leaveapplicationreport').html(response.render);
      for (let key in passData) {
        if (passData.hasOwnProperty(key) && (key != 'status')) {
          jQuery('#' + key).removeClass('is-invalid');
          jQuery('#' + key).parent().children('.select2-container').removeClass('is-invalid');
          jQuery('#' + key).parent().children('.select2-container').parent().removeClass('text-danger');
          jQuery('#' + key).parent().removeClass('text-danger');
        }
      }
    } else {
      for (let key in passData) {
        if (passData.hasOwnProperty(key) && (key != 'status')) {
          jQuery('#' + key).removeClass('is-invalid');
          jQuery('#' + key).parent().children('.select2-container').removeClass('is-invalid');
          jQuery('#' + key).parent().children('.select2-container').parent().removeClass('text-danger');
          jQuery('#' + key).parent().removeClass('text-danger');
        }
      }

      for (let key in response) {
        if (response.hasOwnProperty(key) && (key != 'status')) {
          if (jQuery('.select2-container').is('#s2id_' + key)) {
            jQuery('#' + key).parent().children('.select2-container').addClass('is-invalid');
            jQuery('#' + key).parent().children('.select2-container').parent().addClass('text-danger');
          } else {
            jQuery('#' + key).addClass('is-invalid');
            jQuery('#' + key).parent().addClass('text-danger');
          }
        }
      }
    }
  }
});
