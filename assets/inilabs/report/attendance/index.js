jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
  });

  jQuery(function() {
    jQuery('#attendancetype').val(0);
  });

  jQuery(document).on('change', '#attendancetype, #date', function() {
    jQuery('#load_attendancereport').html('');
  });

  jQuery(document).on('click','#get_attendancereport', function() {
    let attendancetype= jQuery('#attendancetype').val();
    let date  = jQuery('#date').val();
    let error = 0;

    let field = {
      'attendancetype': attendancetype,
      'date'   : date,
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
      url: THEMEBASEURL+'attendancereport/getattendancereport',
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
      jQuery('#load_attendancereport').html(response.render);
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




