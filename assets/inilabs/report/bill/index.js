jQuery(document).ready(function () {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datepicker').datepicker({
    format : 'dd-mm-yyyy',
    autoclose : true
  });

  jQuery('#billcategoryID').change(function () {
    let billcategoryID = jQuery(this).val();
    if (billcategoryID === '0') {
      jQuery('#billlabelID').html('<option value="0">— ' + billreport_please_select + ' —</option>');
    } else {
      jQuery.ajax({
        type : 'POST',
        url : THEMEBASEURL + 'bill/get_billlabel',
        data : {'billcategoryID' : billcategoryID, [ CSRFNAME ] : CSRFHASH},
        dataType : 'html',
        success : function (data) {
          jQuery('#billlabelID').html(data);
        }
      });
    }
  });

  jQuery(document).on('change', '#billcategoryID, #billlabelID, #uhid, #paymentstatus, #from_date, #to_date', function () {
    jQuery('#load_billreport').html('');
  });

  jQuery(document).on('click', '#get_billreport', function () {
    let billcategoryID = jQuery('#billcategoryID').val();
    let billlabelID = jQuery('#billlabelID').val();
    let uhid = jQuery('#uhid').val();
    let paymentstatus = jQuery('#paymentstatus').val();
    let from_date = jQuery('#from_date').val();
    let to_date = jQuery('#to_date').val();
    let error = 0;

    let field = {
      'billcategoryID' : billcategoryID,
      'billlabelID' : billlabelID,
      'uhid' : uhid,
      'paymentstatus' : paymentstatus,
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
      url : THEMEBASEURL + 'billreport/getbillreport',
      data : passData,
      dataType : 'html',
      success : function (data) {
        let response = JSON.parse(data);
        renderLoader(response, passData);
      }
    });
  }

  function renderLoader(response, passData) {
    if (response.status) {
      jQuery('#load_billreport').html(response.render);
      for (let key in passData) {
        if (passData.hasOwnProperty(key) && (key !== 'status')) {
          jQuery('#' + key).removeClass('is-invalid');
          jQuery('#' + key).parent().children('.select2-container').removeClass('is-invalid');
          jQuery('#' + key).parent().children('.select2-container').parent().removeClass('text-danger');
          jQuery('#' + key).parent().removeClass('text-danger');
        }
      }
    } else {
      for (let key in passData) {
        if (passData.hasOwnProperty(key) && (key !== 'status')) {
          jQuery('#' + key).removeClass('is-invalid');
          jQuery('#' + key).parent().children('.select2-container').removeClass('is-invalid');
          jQuery('#' + key).parent().children('.select2-container').parent().removeClass('text-danger');
          jQuery('#' + key).parent().removeClass('text-danger');
        }
      }

      for (let key in response) {
        if (response.hasOwnProperty(key) && (key !== 'status')) {
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


