jQuery(document).ready(function () {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datepicker').datepicker({
    format : 'dd-mm-yyyy',
    autoclose : true
  });

  jQuery(document).on('change', '#testcategoryID', function () {
    let testcategoryID = jQuery('#testcategoryID').val();
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'testreport/get_test_label',
      data : {'testcategoryID' : testcategoryID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#testlabelID').html(data);
      }
    });
  });

  jQuery(document).on('change', '#testcategoryID, #testlabelID, #billID, #from_date, #to_date', function () {
    jQuery('#load_testreport').html('');
  });

  jQuery(document).on('click', '#get_testreport', function () {
    let testcategoryID = jQuery('#testcategoryID').val();
    let testlabelID = jQuery('#testlabelID').val();
    let billID = jQuery('#billID').val();
    let from_date = jQuery('#from_date').val();
    let to_date = jQuery('#to_date').val();
    let error = 0;

    let field = {
      'testcategoryID' : testcategoryID,
      'testlabelID' : testlabelID,
      'billID' : billID,
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
      url : THEMEBASEURL + 'testreport/gettestreport',
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
      jQuery('#load_testreport').html(response.render);
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
