jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
  });

  jQuery(document).on('change', '#wardID', function () {
    let wardID = jQuery(this).val();
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'admissionreport/get_bed',
      data : {'wardID' : wardID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#bedID').html(data);
      }
    });
  });

  jQuery(function() {
    jQuery('#doctorID').val(0);
    jQuery('#patientID').val(0);
    jQuery('#wardID').val(0);
    jQuery('#bedID').val(0);
    jQuery('#casualty').val(0);
    jQuery('#status').val(0);
  });

  jQuery(document).on('change', '#uhid, #doctorID, #patientID, #wardID, #bedID, #casualty, #status, #from_date, #to_date', function() {
    jQuery('#load_admissionreport').html('');
  });

  jQuery(document).on('click','#get_admissionreport', function() {
    let doctorID  = jQuery('#doctorID').val();
    let patientID = jQuery('#patientID').val();
    let wardID    = jQuery('#wardID').val();
    let bedID     = jQuery('#bedID').val();
    let casualty  = jQuery('#casualty').val();
    let status    = jQuery('#status').val();
    let from_date = jQuery('#from_date').val();
    let to_date   = jQuery('#to_date').val();
    let error     = 0;

    let field = {
      'doctorID'  : doctorID,
      'patientID' : patientID,
      'wardID'    : wardID,
      'bedID'     : bedID,
      'casualty'  : casualty,
      'status'    : status,
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
      type : 'POST',
      url : THEMEBASEURL + 'admissionreport/getadmissionreport',
      data : passData,
      dataType : 'html',
      success : function (data) {
        let response = JSON.parse(data);
        renderLoder(response, passData);
      }
    });
  }

  function renderLoder(response, passData) {
    if(response.status) {
      jQuery('#load_admissionreport').html(response.render);
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