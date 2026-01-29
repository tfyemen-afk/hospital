
jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.monthpicker').datepicker({
    format: 'mm-yyyy',
    viewMode: 'months',
    minViewMode: 'months',
    autoclose: true,
    todayBtn: false
  });

  jQuery('#roleID').val(0);
  jQuery('#userID').val(0);

  jQuery(document).on('change', '#roleID', function() {
    let roleID = jQuery('#roleID').val();
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'attendanceoverviewreport/get_user',
      data : {'roleID' : roleID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        jQuery('#userID').html(data);
      }
    });
  });

  jQuery(document).on('change', '#roleID, #userID, #month', function() {
    jQuery('#load_attendanceoverviewreport').html('');
  });

  jQuery(document).on('click','#get_attendanceoverviewreport', function() {
    let roleID  = jQuery('#roleID').val();
    let userID  = jQuery('#userID').val();
    let month   = jQuery('#month').val();
    let error   = 0;
    let field = {
      'roleID' : roleID,
      'userID' : userID,
      'month' : month,
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
      url : THEMEBASEURL + 'attendanceoverviewreport/getattendanceoverviewreport',
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
      jQuery('#load_attendanceoverviewreport').html(response.render);
      for (let key in passData) {
        if (passData.hasOwnProperty(key) && (key != 'status')) {
          jQuery('#'+key).removeClass('is-invalid');
          jQuery('#'+key).parent().children('.select2-container').removeClass('is-invalid');
          jQuery('#'+key).parent().children('.select2-container').parent().removeClass('text-danger');
          jQuery('#'+key).parent().removeClass('text-danger');
        }
      }
    } else {
      for (let key in passData) {
        if (passData.hasOwnProperty(key) && (key != 'status')) {
          jQuery('#'+key).removeClass('is-invalid');
          jQuery('#'+key).parent().children('.select2-container').removeClass('is-invalid');
          jQuery('#'+key).parent().children('.select2-container').parent().removeClass('text-danger');
          jQuery('#'+key).parent().removeClass('text-danger');
        }
      }

      for (let key in response) {
        if (response.hasOwnProperty(key) && (key != 'status')) {
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
