jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery('#operation_date').datetimepicker({
    autoclose : true,
    format : 'dd-mm-yyyy HH:ii P',
    showMeridian : 'day',
    todayHighlight : true,
  });

  jQuery(document).on('change', '#doctorID', function () {
    let doctorID = parseInt(jQuery(this).val());
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL+'operationtheatre/get_doctor',
      dataType : 'html',
      data : {'doctorID' : doctorID, [ CSRFNAME ] : CSRFHASH},
      success : function (data) {
        jQuery('#assistant_doctor_1').html(data);
      }
    });
  });

  jQuery(document).on('change', '#assistant_doctor_1', function () {
    let doctorID = parseInt(jQuery('#doctorID').val());
    let assistant_doctor_1 = parseInt(jQuery(this).val());
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL+'operationtheatre/get_doctor',
      dataType : 'html',
      data : {'doctorID' : doctorID, 'assistant_doctor_1' : assistant_doctor_1, [ CSRFNAME ] : CSRFHASH},
      success : function (data) {
        jQuery('#assistant_doctor_2').html(data);
      }
    });
  });

  let setdoctorID = doctorID;
  let setassistant_doctor_1 = assistant_doctor_1;
  let setassistant_doctor_2 = assistant_doctor_2;

  if (setdoctorID) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL+'operationtheatre/get_doctor',
      dataType : 'html',
      data : {'doctorID' : setdoctorID, [ CSRFNAME ] : CSRFHASH},
      success : function (data) {
        jQuery('#assistant_doctor_1').html(data);
        jQuery('#assistant_doctor_1').val(setassistant_doctor_1).trigger('change');
      }
    });
  }

  if (setdoctorID && setassistant_doctor_1) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL+'operationtheatre/get_doctor',
      dataType : 'html',
      data : {'doctorID' : setdoctorID, 'assistant_doctor_1' : setassistant_doctor_1, [ CSRFNAME ] : CSRFHASH},
      success : function (data) {
        jQuery('#assistant_doctor_2').html(data);
        jQuery('#assistant_doctor_2').val(setassistant_doctor_2).trigger('change');
      }
    });
  }
});





