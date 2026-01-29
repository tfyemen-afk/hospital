jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();

  jQuery(document).on('change', '#testcategoryID', function() {
    let testcategoryID = jQuery(this).val();

    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL+'test/get_label',
      data : { 'testcategoryID' : testcategoryID, [ CSRFNAME ] : CSRFHASH },
      dataType : 'html',
      success : function (data) {
        jQuery('#testlabelID').html(data);
      },
    });
  });

  jQuery(document).on('click', '.viewModalBtn', function() {
    let testID = jQuery(this).attr('id');
    if(parseInt(testID)) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'test/view',
        data: { 'testID': testID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          jQuery('.viewTestModal').html(data);
        },
      });
    }
  });

  jQuery(document).on('change', '#fileuploadinput', function () {
    if(this.files.length > 0) {
      jQuery('.label-text-hide').text(this.files[0].name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });

  jQuery(document).on('click', '#save_test_file', function(event) {
    event.preventDefault();
    let error      = 0;
    let fileupload = jQuery('#fileuploadinput').val();
    if(fileupload === '') {
      error++;
      jQuery('#fileuploadinput').parent().parent().addClass('text-danger').addClass('is-invalid');
      jQuery('#fileuploadinput-error').text('The file field is required.');
    } else {
      jQuery('#fileuploadinput').parent().parent().removeClass('text-danger').removeClass('is-invalid');;
      jQuery('#fileuploadinput-error').text('');
    }

    if(error === 0) {
      let formData = new FormData(jQuery('#test_file')[0]);
      formData.append([CSRFNAME], CSRFHASH);
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'test/save_test_file',
        data: formData,
        async: true,
        dataType: 'html',
        success: function(data) {
          let response = JSON.parse(data);
          if(response.status) {
            location.reload();
          } else {
            toastr.error(response.message);
          }
        },
        cache: false,
        contentType: false,
        processData: false
      });
    }
  });

  jQuery(document).on('click', '.uploadModalBtn', function() {
    let testID = jQuery(this).attr('id');
    jQuery('#testID').val(testID);
    if(parseInt(testID)) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'test/get_test_file',
        data: {'testID': testID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          jQuery('#modalBodyTable').html(data);
        },
      });
    }
  });
});