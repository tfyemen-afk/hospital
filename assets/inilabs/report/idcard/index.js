jQuery(document).ready(function () {
  jQuery('.select2').select2();

  jQuery(function () {
    'use strict';
    jQuery('#roleID').val(0);
    jQuery('#userID').val(0);
    jQuery('#type').val(0);
    jQuery('#background').val(0);
  });

  jQuery('#userDiv').hide();
  jQuery(document).on('change', '#roleID', function () {
    'use strict';
    jQuery('#load_idcardreport').html('');
    jQuery('#userDiv').show('slow');
    let roleID = jQuery(this).val();
    if (roleID > 0) {
      jQuery.ajax({
        type : 'POST',
        url : THEMEBASEURL+'idcardreport/get_user',
        data : {'roleID' : roleID, [ CSRFNAME ] : CSRFHASH },
        success : function (data) {
          jQuery('#userID').html(data);
        }
      });
    } else {
      jQuery('#userDiv').hide('slow');
    }
  });

  jQuery(document).on('change', '#userID', function () {
    jQuery('#load_idcardreport').html('');
  });

  jQuery(document).on('change', '#type', function () {
    jQuery('#load_idcardreport').html('');
  });

  jQuery(document).on('change', '#background', function () {
    jQuery('#load_idcardreport').html('');
  });

  jQuery(document).on('click', '#get_idcardreport', function () {
    let roleID = jQuery('#roleID').val();
    let userID = jQuery('#userID').val();
    let type = jQuery('#type').val();
    let background = jQuery('#background').val();
    let error = 0;

    let field = {
      'roleID' : roleID,
      'userID' : userID,
      'type' : type,
      'background' : background,
      [ CSRFNAME ] : CSRFHASH
    };

    if (field[ 'roleID' ] == 0) {
      jQuery('#roleID').parent().children('.select2-container').addClass('is-invalid');
      error++;
    } else {
      jQuery('#roleID').parent().children('.select2-container').removeClass('is-invalid');
    }

    if (field[ 'type' ] == 0) {
      jQuery('#type').parent().children('.select2-container').addClass('is-invalid');
      error++;
    } else {
      jQuery('#type').parent().children('.select2-container').removeClass('is-invalid');
    }

    if (field[ 'background' ] == 0) {
      jQuery('#background').parent().children('.select2-container').addClass('is-invalid');
      error++;
    } else {
      jQuery('#background').parent().children('.select2-container').removeClass('is-invalid');
    }

    if (error == 0) {
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
      url : THEMEBASEURL+'idcardreport/getidcardreport',
      data : passData,
      dataType : 'html',
      success : function (data) {
        let response = JSON.parse(data);
        renderLoder(response, passData);
      }
    });
  }

  function renderLoder(response, passData) {
    'use strict';
    if (response.status) {
      jQuery('#load_idcardreport').html(response.render);
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
