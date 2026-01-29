jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery(document).on('change', '.roleID', function(){
    let roleID = jQuery(this).val();
    let url = '';
    if(parseInt(roleID)) {
      url = THEMEBASEURL+'managesalary/index/'+roleID;
    } else {
      url = THEMEBASEURL+'managesalary/index';
    }
    window.location.href = url;
  });

  jQuery(document).on('change', '#salary', function() {
    let salary = jQuery(this).val();
    console.log(typeof salary);
    if(salary === '0') {
      jQuery('#template').html('<option value="0">'+managesalary_select_template+'</option>');
    } else {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'managesalary/templatecall',
        data: { 'salary' : salary, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          jQuery('#template').html(data);
        }
      });
    }
  });

  jQuery(document).on('change', '#salary_edit', function () {
    let salary = jQuery(this).val();
    if (salary === '0') {
      jQuery('#template_edit').html('<option value="0">' + managesalary_select_template + '</option>');
    } else {
      jQuery.ajax({
        type : 'POST',
        url : THEMEBASEURL + 'managesalary/templatecall',
        data : {'salary' : salary, [ CSRFNAME ] : CSRFHASH},
        dataType : 'html',
        success : function (data) {
          jQuery('#template_edit').html(data);
        }
      });
    }
  });

  let addUserID = 0;
  jQuery(document).on('click', '.addModalBtn', function() {
    addUserID = jQuery(this).attr('id');
  });

  let editManageSalaryID = 0;
  jQuery(document).on('click', '.editModalBtn', function () {
    let managesalaryID = jQuery(this).attr('id');
    editManageSalaryID = managesalaryID;
    jQuery('#getmanagesalaryid').val(managesalaryID);

    jQuery.ajax({
      dataType : 'json',
      type : 'POST',
      url : THEMEBASEURL + 'managesalary/editinfocall',
      data : { 'managesalaryid' : managesalaryID, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        let response = JSON.parse(data);
        if (response.status) {
          let salaryID = response.managesalary[ 'salary' ];
          let templateID = response.managesalary[ 'template' ];
          jQuery('#salary_edit').val(salaryID).select2();
          jQuery.ajax({
            type : 'POST',
            url : THEMEBASEURL + 'managesalary/templatecall',
            data : {'salary' : salaryID, [ CSRFNAME ] : CSRFHASH },
            dataType : 'html',
            success : function (data) {
              jQuery('#template_edit').html(data);
              jQuery('#template_edit').val(templateID).select2();
            }
          });
        } else {
          jQuery('#salary_edit').val(0);
        }
      }
    });
  });

  jQuery(document).on('click', '.add_managesalary', function() {
    let userID = addUserID;
    let formData = new FormData(jQuery('#getFormData')[0]);
    formData.append('userID', userID);
    formData.append([CSRFNAME], CSRFHASH);
    jQuery.ajax({
      type: 'POST',
      url: THEMEBASEURL+'managesalary/addsalary',
      data: formData,
      dataType: 'html',
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
        let response = JSON.parse(data);
        if(response.status) {
          window.location.reload();
        } else {
          if(response.salary) {
            jQuery('#error_salary_add_div').addClass('text-danger');
            jQuery('#salary').addClass('is-invalid');
            jQuery('#error_salary').html(response.salary);
          } else {
            jQuery('#error_salary_add_div').removeClass('text-danger');
            jQuery('#salary').removeClass('is-invalid');
            jQuery('#error_salary').html('');
          }

          if(response.template) {
            jQuery('#error_salary_template_add_div').addClass('text-danger');
            jQuery('#template').addClass('is-invalid');
            jQuery('#error_salary_template').html(response.template);
          } else {
            jQuery('#error_salary_template_add_div').removeClass('text-danger');
            jQuery('#template').removeClass('is-invalid');
            jQuery('#error_salary_template').html();
          }
        }
      }
    });
  });

  jQuery(document).on('click', '.edit_managesalary', function () {
    let salary = jQuery('#salary_edit').val();
    let template = jQuery('#template_edit').val();
    let managesalaryID = editManageSalaryID;

    jQuery.ajax({
      dataType : 'json',
      type : 'POST',
      url : THEMEBASEURL + 'managesalary/updatesalary',
      data : {'managesalaryID' : managesalaryID, 'salary' : salary, 'template' : template, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        let response = JSON.parse(data);
        if (response.status) {
          window.location.reload();
        } else {
          if (response.salary) {
            jQuery('#error_salary_template_edit').html(response.template);
          } else {
            jQuery('#error_salary_template_edit_div').removeClass('text-danger');
            jQuery('#template_edit').removeClass('is-invalid');
            jQuery('#error_salary_template_edit').html();
          }
        }
      }
    });
  });
});