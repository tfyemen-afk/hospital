
jQuery(document).ready(function() {
  jQuery('.select2').select2();
  jQuery('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
  });

  jQuery(document).on('change','#wardID, #bedtypeID', function() {
    let wardID    = jQuery('#wardID').val();
    let bedtypeID = jQuery('#bedtypeID').val();

    jQuery.ajax({
      type: 'POST',
      url: THEMEBASEURL+'bedreport/get_bed',
      data: {'wardID' : wardID, 'bedtypeID' : bedtypeID, [CSRFNAME] : CSRFHASH},
      dataType: 'html',
      success: function (data) {
        jQuery('#bedID').html(data);
      }
    });
  });

  jQuery(document).on('change', '#wardID, #bedtypeID, #bedID, #statusID', function() {
    jQuery('#load_bedreport').html('');
  });

  jQuery(document).on('click','#get_bedreport', function() {
    let wardID    = jQuery('#wardID').val();
    let bedtypeID = jQuery('#bedtypeID').val();
    let bedID     = jQuery('#bedID').val();
    let statusID  = jQuery('#statusID').val();
    let error     = 0;

    let field = {
      'wardID'    : wardID,
      'bedtypeID' : bedtypeID,
      'bedID'     : bedID,
      'statusID'  : statusID,
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
      url : THEMEBASEURL + 'bedreport/getbedreport',
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
      jQuery('#load_bedreport').html(response.render);
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