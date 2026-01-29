jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
  });

  jQuery(function() {
    jQuery('#patient_type').val(0);
    jQuery('#statusID').val(0);
  });

  jQuery('.uhidDIV').hide();
  jQuery(document).on('change', '#patient_type', function() {
    jQuery('#load_medicinesalereport').html('');
    let patient_type = jQuery('#patient_type').val();
    if(patient_type === '1' || patient_type === '2') {
      jQuery('.uhidDIV').show('slow');
    } else {
      jQuery('.uhidDIV').hide('slow');
    }
  });

  jQuery(document).on('change', '#uhid, statusID, #from_date, #to_date', function() {
    jQuery('#load_medicinesalereport').html('');
  });

  jQuery(document).on('click','#get_medicinesalereport', function() {
    let patient_type = jQuery('#patient_type').val();
    let uhid      = jQuery('#uhid').val();
    let statusID  = jQuery('#statusID').val();
    let from_date = jQuery('#from_date').val();
    let to_date   = jQuery('#to_date').val();
    let error     = 0;

    let field = {
      'patient_type' : patient_type,
      'uhid'      : uhid,
      'statusID'  : statusID,
      'from_date' : from_date,
      'to_date'   : to_date,
      [CSRFNAME] : CSRFHASH
    };

    if(error === 0 ) {
      makingPostDataPreviousofAjaxCall(field);
    }
  });

  function makingPostDataPreviousofAjaxCall(field) {
    let passData = field;
    ajaxCall(passData);
  }

  function ajaxCall(passData) {
    jQuery.ajax({
      type:'POST',
      url: THEMEBASEURL+'medicinesalereport/getmedicinesalereport',
      data:passData,
      dataType:'html',
      success:function(data) {
      let response = JSON.parse(data);
      renderLoder(response, passData);
    }
  });
  }

  function renderLoder(response, passData) {
    if(response.status) {
      jQuery('#load_medicinesalereport').html(response.render);
      for (let key in passData) {
        if (passData.hasOwnProperty(key) && (key !== 'status')) {
          jQuery('#'+key).removeClass('is-invalid');
          jQuery('#'+key).parent().children('.select2-container').removeClass('is-invalid');
          jQuery('#'+key).parent().children('.select2-container').parent().removeClass('text-danger');
          jQuery('#'+key).parent().removeClass('text-danger');
        }
      }
    } else {
      for (let key in passData) {
        if (passData.hasOwnProperty(key) && (key !== 'status')) {
          jQuery('#'+key).removeClass('is-invalid');
          jQuery('#'+key).parent().children('.select2-container').removeClass('is-invalid');
          jQuery('#'+key).parent().children('.select2-container').parent().removeClass('text-danger');
          jQuery('#'+key).parent().removeClass('text-danger');
        }
      }

      for (let key in response) {
        if (response.hasOwnProperty(key) && (key !== 'status')) {
          if(jQuery('.select2-container').is('#s2id_'+key)) {
            jQuery('#'+key).parent().children('.select2-container').addClass('is-invalid');
            jQuery('#'+key).parent().children('.select2-container').parent().addClass('text-danger');
          } else {
            jQuery('#'+key).addClass('is-invalid');
            jQuery('#'+key).parent().addClass('text-danger');
          }
        }
      }
    }
  }
});

