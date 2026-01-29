jQuery(document).on({
  focus : function () {
    'use strict';
    let basic_salary = jQuery(this).val();
    console.log(typeof basic_salary);
    if (jQuery.isNumeric(basic_salary)) {
      if (basic_salary >= 0) {
        if (basic_salary === '') {
          basic_salary = 0;
        } else {
          if (basic_salary.length > 11) {
            basic_salary = parseFloat(0);
          } else {
            basic_salary = parseFloat(jQuery(this).val());
          }
        }

        let net_salary = jQuery('#net_salary').val();
        if (net_salary === '') {
          net_salary = 0;
        } else {
          net_salary = parseFloat(jQuery('#net_salary').val());
        }

        let gross_salary = jQuery('#gross_salary').val();
        if (gross_salary === '') {
          gross_salary = 0;
        } else {
          gross_salary = parseFloat(jQuery('#gross_salary').val());
        }

        let equ = net_salary - basic_salary;
        jQuery('#net_salary').val(parseFloat(equ));

        let gross_salary_equ = gross_salary - basic_salary;
        jQuery('#gross_salary').val(parseFloat(gross_salary_equ));
      }
    }
  },
  blur : function () {
    'use strict';
    let basic_salary = jQuery(this).val();
    if (zeroOrNull(basic_salary, '#basic_salary_error')) {
      let net_salary = jQuery('#net_salary').val();
      let gross_salary = jQuery('#gross_salary').val();

      if (basic_salary === '') {
        basic_salary = 0;
      } else {
        if (basic_salary.length > 11) {
          basic_salary = '';
          jQuery(this).val('');
        } else {
          basic_salary = parseFloat(jQuery(this).val());
        }
      }

      if (net_salary === '') {
        net_salary = 0;
      } else {
        net_salary = parseFloat(jQuery('#net_salary').val());
      }

      if (gross_salary === '') {
        gross_salary = 0;
      } else {
        gross_salary = parseFloat(jQuery('#gross_salary').val());
      }

      let equ = net_salary + basic_salary;
      jQuery('#net_salary').val(parseFloat(equ));

      let gross_salary_equ = gross_salary + basic_salary;
      jQuery('#gross_salary').val(parseFloat(gross_salary_equ));
    }
  }
}, '#basic_salary');

jQuery(document).on('blur', '#overtime_rate', function () {
  'use strict';
  let overtime_rate = jQuery(this).val();
  if (overtime_rate.length > 11) {
    jQuery(this).val('');
  } else {
    zeroOrNull(overtime_rate, '#overtime_rate_error');
  }
});

jQuery(document).on('blur', '#salary_grades', function () {
  'use strict';
  let salary_grades = jQuery(this).val();
  if (salary_grades !== '') {
    jQuery('#salary_grades_error').html('').parent().removeClass('text-danger');
  }
});

jQuery(document).on('focus', '.allowancesamount', function () {
  'use strict';
  let allowancesamount = jQuery(this).val();
  let errorID = jQuery(this).parent().parent().children('.errorpointallowances').attr('id');

  if (zeroOrNullExtend(allowancesamount, errorID, 'label', false)) {
    if (allowancesamount === '') {
      allowancesamount = 0;
    } else {
      if (allowancesamount.length > 11) {
        allowancesamount = '';
        jQuery(this).val('');
      } else {
        allowancesamount = parseFloat(jQuery(this).val());
      }
    }

    let net_salary = jQuery('#net_salary').val();
    if (net_salary === '') {
      net_salary = 0;
    } else {
      net_salary = parseFloat(jQuery('#net_salary').val());
    }

    let gross_salary = jQuery('#gross_salary').val();
    if (gross_salary === '') {
      gross_salary = 0;
    } else {
      gross_salary = parseFloat(jQuery('#gross_salary').val());
    }

    let equ = net_salary - allowancesamount;
    jQuery('#net_salary').val(equ);

    let equfg = gross_salary - allowancesamount;
    jQuery('#gross_salary').val(equfg);
  }
});

jQuery(document).on('blur', '.allowancesamount', function () {
  'use strict';
  let allowancesamount = jQuery(this).val();
  let errorID = jQuery(this).parent().parent().children('.errorpointallowances').attr('id');
  errorID = '#' + errorID;

  if (zeroOrNullExtend(allowancesamount, errorID, salarytemplate_allowances_val)) {
    if (allowancesamount === '') {
      allowancesamount = 0;
    } else {
      if (allowancesamount.length > 11) {
        allowancesamount = '';
        jQuery(this).val('');
      } else {
        allowancesamount = parseFloat(jQuery(this).val());
      }
    }

    let net_salary = jQuery('#net_salary').val();
    if (net_salary === '') {
      net_salary = 0;
    } else {
      net_salary = parseFloat(jQuery('#net_salary').val());
    }

    let gross_salary = jQuery('#gross_salary').val();

    if (gross_salary === '') {
      gross_salary = 0;
    } else {
      gross_salary = parseFloat(jQuery('#gross_salary').val());
    }

    let equ = net_salary + allowancesamount;
    jQuery('#net_salary').val(equ);

    let equfg = gross_salary + allowancesamount;
    jQuery('#gross_salary').val(equfg);
  }
});

jQuery(document).on('focus', '.deductionsamount', function () {
  'use strict';
  let deductionsamount = jQuery(this).val();
  let errorID = jQuery(this).parent().parent().children('.errorpointallowances').attr('id');

  if (zeroOrNullExtend(deductionsamount, errorID, 'label', false)) {
    if (deductionsamount === '') {
      deductionsamount = 0;
    } else {
      if (deductionsamount.length > 11) {
        deductionsamount = '';
        jQuery(this).val('');
      } else {
        deductionsamount = parseFloat(jQuery(this).val());
      }
    }

    let net_salary = jQuery('#net_salary').val();
    if (net_salary === '') {
      net_salary = 0;
    } else {
      net_salary = parseFloat(jQuery('#net_salary').val());
    }

    let total_deduction = jQuery('#total_deduction').val();
    if (total_deduction === '') {
      total_deduction = 0;
    } else {
      total_deduction = parseFloat(jQuery('#total_deduction').val());
    }

    let equ = net_salary + deductionsamount;
    jQuery('#net_salary').val(equ);

    let equfg = total_deduction - deductionsamount;
    jQuery('#total_deduction').val(equfg);
  }
});

jQuery(document).on('blur', '.deductionsamount', function () {
  'use strict';
  let deductionsamount = jQuery(this).val();
  let errorID = jQuery(this).parent().parent().children('.errorpointdeductions').attr('id');
  errorID = '#' + errorID;

  if (zeroOrNullExtend(deductionsamount, errorID, salarytemplate_deductions_val)) {
    if (deductionsamount === '') {
      deductionsamount = 0;
    } else {
      if (deductionsamount.length > 11) {
        deductionsamount = '';
        jQuery(this).val('');
      } else {
        deductionsamount = parseFloat(jQuery(this).val());
      }
    }

    let net_salary = jQuery('#net_salary').val();
    if (net_salary === '') {
      net_salary = 0;
    } else {
      net_salary = parseFloat(jQuery('#net_salary').val());
    }

    let total_deduction = jQuery('#total_deduction').val();

    if (total_deduction === '') {
      total_deduction = 0;
    } else {
      total_deduction = parseFloat(jQuery('#total_deduction').val());
    }

    let equ = net_salary - deductionsamount;
    jQuery('#net_salary').val(equ);

    let equfg = total_deduction + deductionsamount;
    jQuery('#total_deduction').val(equfg);
  }
});

jQuery(document).on('click', '#addSalaryTemplate', function () {
  'use strict';
  let salary_grades = jQuery('#salary_grades').val();
  let basic_salary = jQuery('#basic_salary').val();
  let overtime_rate = jQuery('#overtime_rate').val();
  let error = 0;
  let allowances_number = jQuery('.allowancesfield').length;
  let deductions_number = jQuery('.deductionsfield').length;

  for (let i = 1; i <= allowances_number; i++) {
    if (jQuery('#allowancesamount' + i).val() !== '') {
      if (jQuery.isNumeric(jQuery('#allowancesamount' + i).val())) {
        if (jQuery('#allowancesamount' + i).val().length > 11) {
          error++;
          jQuery('#allowanceserror' + i).html('The ' + salarytemplate_allowances_val + ' field cannot exceed 11 characters in length.');
          jQuery('#allowanceserror' + i).parent().addClass('text-danger');
          jQuery('#allowanceserror' + i).addClass('text-danger');
        } else {
          jQuery('#allowanceserror' + i).html('');
          jQuery('#allowanceserror' + i).parent().removeClass('text-danger');
          jQuery('#allowanceserror' + i).removeClass('text-danger');
        }
      } else {
        error++;
        jQuery('#allowanceserror' + i).html('The ' + salarytemplate_allowances_val + ' field is only number.');
        jQuery('#allowanceserror' + i).parent().addClass('text-danger');
        jQuery('#allowanceserror' + i).addClass('text-danger');
      }
    }
  }

  for (let j = 1; j <= deductions_number; j++) {
    if (jQuery('#deductionsamount' + j).val() !== '') {
      if (jQuery.isNumeric(jQuery('#deductionsamount' + j).val())) {
        if (jQuery('#deductionsamount' + j).val().length > 11) {
          error++;
          jQuery('#deductionserror' + j).html('The ' + salarytemplate_deductions_val + ' field cannot exceed 11 characters in length.');
          jQuery('#deductionserror' + j).parent().addClass('text-danger');
          jQuery('#deductionserror' + j).addClass('text-danger');
        } else {
          jQuery('#deductionserror' + j).html('');
          jQuery('#deductionserror' + j).parent().removeClass('text-danger');
          jQuery('#deductionserror' + j).removeClass('text-danger');
        }
      } else {
        error++;
        jQuery('#deductionserror' + j).html('The ' + salarytemplate_deductions_val + ' field is only number.');
        jQuery('#deductionserror' + j).parent().addClass('text-danger');
        jQuery('#deductionserror' + j).addClass('text-danger');
      }
    }
  }

  if (salary_grades === '') {
    jQuery('#salary_grades_error').html('The Salary Grades field is required.');
    jQuery('#salary_grades_error').parent().addClass('text-danger');
    error++;
  } else {
    if (salary_grades.length > 128) {
      error++;
      jQuery('#salary_grades_error').html('The Salary Grades field cannot exceed 128 characters in length.');
      jQuery('#salary_grades_error').parent().addClass('text-danger');
    } else {
      jQuery('#salary_grades_error').html('');
      jQuery('#salary_grades_error').parent().removeClass('text-danger');
    }
  }

  if (basic_salary === '') {
    jQuery('#basic_salary_error').html('The Basic Salary field is required.');
    jQuery('#basic_salary_error').parent().addClass('text-danger');
    error++;
  } else {
    if (jQuery.isNumeric(basic_salary)) {
      if (basic_salary.length > 11) {
        error++;
        jQuery('#basic_salary_error').html('The Basic Salary field cannot exceed 11 characters in length.');
        jQuery('#basic_salary_error').parent().addClass('text-danger');
      } else {
        jQuery('#basic_salary_error').html('');
        jQuery('#basic_salary_error').parent().removeClass('text-danger');
      }
    } else {
      error++;
      jQuery('#basic_salary_error').html('The Basic Salary field is only number.');
      jQuery('#basic_salary_error').parent().addClass('text-danger');
    }
  }

  if (overtime_rate === '') {
    jQuery('#overtime_rate_error').html('The Overtime Rate (Per Hour) field is required.');
    jQuery('#overtime_rate_error').parent().addClass('text-danger');
    error++;
  } else {
    if (jQuery.isNumeric(overtime_rate)) {
      if (overtime_rate.length > 11) {
        error++;
        jQuery('#overtime_rate_error').html('The Overtime Rate (Per Hour) field cannot exceed 11 characters in length.');
        jQuery('#overtime_rate_error').parent().addClass('text-danger');
      } else {
        jQuery('#overtime_rate_error').html('');
        jQuery('#overtime_rate_error').parent().removeClass('text-danger');
      }
    } else {
      error++;
      jQuery('#overtime_rate_error').html('The Overtime Rate (Per Hour) field is only number.');
      jQuery('#overtime_rate_error').parent().addClass('text-danger');
    }
  }

  if (error === 0) {
    let formData = new FormData(jQuery('#templateDataForm')[ 0 ]);
    formData.append('allowances_number', allowances_number);
    formData.append('deductions_number', deductions_number);
    formData.append([ CSRFNAME ], CSRFHASH);

    jQuery.ajax({
      type : 'POST',
      dataType : 'json',
      url : THEMEBASEURL + 'salarytemplate/add_ajax',
      data : formData,
      async : false,
      success : function (response) {
        if (response.status === 'success') {
          window.location = THEMEBASEURL + 'salarytemplate/index';
        } else {
          if (response.errors[ 'salary_grades' ]) {
            jQuery('#salary_grades_error').html(response.errors[ 'salary_grades' ]);
            jQuery('#salary_grades_error').parent().addClass('text-danger');
          }
        }
      },
      cache : false,
      contentType : false,
      processData : false
    });
  }
});

function zeroOrNull(value, error, status = true) {
  if (value !== '') {
    if (jQuery.isNumeric(value)) {
      jQuery(error).html('');
      jQuery(error).parent().removeClass('text-danger');

      if (value >= 0) {
        jQuery(error).html('');
        jQuery(error).parent().removeClass('text-danger');
        return true;
      } else {
        if (status) {
          jQuery(error).html('The Basic Salary field is not negative number.');
          jQuery(error).parent().addClass('text-danger');
        }
      }
    } else {
      if (status) {
        jQuery(error).html('The Basic Salary field is only number.');
        jQuery(error).parent().addClass('text-danger');
      }
    }
  } else {
    jQuery(error).html('');
    jQuery(error).parent().removeClass('text-danger');
  }
}

function zeroOrNullExtend(value, errorID, errorField = 'field', status = true) {
  if (value !== '') {
    if (jQuery.isNumeric(value)) {
      jQuery(errorID).html('');
      jQuery(errorID).parent().removeClass('text-danger');

      if (value >= 0) {
        jQuery(errorID).html('');
        jQuery(errorID).parent().removeClass('text-danger');
        return true;
      } else {
        if (status) {
          jQuery(errorID).html('The ' + errorField + ' field is not negative number.');
          jQuery(errorID).parent().addClass('text-danger');
          jQuery(errorID).addClass('text-danger');
        }
      }
    } else {
      if (status) {
        jQuery(errorID).html('The ' + errorField + ' field is only number.');
        jQuery(errorID).parent().addClass('text-danger');
        jQuery(errorID).addClass('text-danger');
      }
    }
  } else {
    jQuery(errorID).html('');
    jQuery(errorID).parent().removeClass('text-danger');
  }
}

function addAllowances() {
  let label = [];
  let amount = [];
  let count = jQuery('.allowancesfield').length;

  for (let j = 1; j <= count; j++) {
    label[ j ] = jQuery('#allowanceslabel' + j).val();
    amount[ j ] = jQuery('#allowancesamount' + j).val();
  }

  let totalOption = count + 1;
  let type = 'allowances';
  jQuery('#allowances').children().remove();
  for (let i = 1; i <= totalOption; i++) {
    if (i <= count) {
      jQuery('#allowances').append(formHtmlData(type, i, label[ i ], amount[ i ], '', 1));
    }
    else {
      jQuery('#allowances').append(formHtmlData(type, i, label = '', amount = '', 1, 1));
    }
  }
}

function removeAllowances(clickedElement) {
  let id = clickedElement.id;
  let label = [];
  let amount = [];
  let count = jQuery('.allowancesfield').length;
  let removeAmount = 0;
  let totalOption = count - 1;

  for (let k = 1; k <= count; k++) {
    if (Number(k) === Number(id)) {
      removeAmount = jQuery('#allowancesamount' + k).val();
    }
  }

  for (let j = 1; j <= totalOption; j++) {
    if (j >= id) {
      let point = j + 1;
      label[ j ] = jQuery('#allowanceslabel' + point).val();
      amount[ j ] = jQuery('#allowancesamount' + point).val();
    } else {
      label[ j ] = jQuery('#allowanceslabel' + j).val();
      amount[ j ] = jQuery('#allowancesamount' + j).val();
    }
  }

  let type = 'allowances';
  jQuery('#allowances').children().remove();
  for (let i = 1; i <= totalOption; i++) {
    if (i !== Number(totalOption)) {
      jQuery('#allowances').append(formHtmlData(type, i, label[ i ], amount[ i ], '', 1));
    } else if (i === 1) {
      jQuery('#allowances').append(formHtmlData(type, i, label[ i ], amount[ i ], 1, ''));
    } else {
      jQuery('#allowances').append(formHtmlData(type, i, label[ i ], amount[ i ], 1, 1));
    }
  }

  let net_salary = jQuery('#net_salary').val();
  if (net_salary === '') {
    net_salary = 0;
  } else {
    net_salary = parseFloat(jQuery('#net_salary').val());
  }

  let gross_salary = jQuery('#gross_salary').val();
  if (gross_salary === '') {
    gross_salary = 0;
  } else {
    gross_salary = parseFloat(jQuery('#gross_salary').val());
  }

  if (removeAmount === '' || removeAmount === 0) {
    removeAmount = 0;
  } else {
    removeAmount = parseFloat(removeAmount);
  }

  let equ = net_salary - removeAmount;
  jQuery('#net_salary').val(equ);

  let equfg = gross_salary - removeAmount;
  jQuery('#gross_salary').val(equfg);
}

function addDeductions() {
  let label = [];
  let amount = [];
  let count = jQuery('.deductionsfield').length;

  for (let j = 1; j <= count; j++) {
    label[ j ] = jQuery('#deductionslabel' + j).val();
    amount[ j ] = jQuery('#deductionsamount' + j).val();
  }

  let totalOption = count + 1;
  let type = 'deductions';
  jQuery('#deductions').children().remove();
  for (let i = 1; i <= totalOption; i++) {
    if (i <= count) {
      jQuery('#deductions').append(formHtmlData(type, i, label[ i ], amount[ i ], '', 1));
    }
    else {
      jQuery('#deductions').append(formHtmlData(type, i, label = '', amount = '', 1, 1));
    }
  }
}

function removeDeductions(clickedElement) {
  let id = clickedElement.id;
  let label = [];
  let amount = [];
  let count = jQuery('.deductionsfield').length;
  let removeAmount = 0;
  let totalOption = count - 1;

  for (let k = 1; k <= count; k++) {
    if (Number(k) === Number(id)) {
      removeAmount = jQuery('#deductionsamount' + k).val();
    }
  }

  for (let j = 1; j <= totalOption; j++) {
    if (j >= id) {
      let point = j + 1;
      label[ j ] = jQuery('#deductionslabel' + point).val();
      amount[ j ] = jQuery('#deductionsamount' + point).val();
    } else {
      label[ j ] = jQuery('#deductionslabel' + j).val();
      amount[ j ] = jQuery('#deductionsamount' + j).val();
    }
  }

  let type = 'deductions';
  jQuery('#deductions').children().remove();
  for (let i = 1; i <= totalOption; i++) {
    if (i !== Number(totalOption)) {
      jQuery('#deductions').append(formHtmlData(type, i, label[ i ], amount[ i ], '', 1));
    } else if (i === 1) {
      jQuery('#deductions').append(formHtmlData(type, i, label[ i ], amount[ i ], 1, ''));
    } else {
      jQuery('#deductions').append(formHtmlData(type, i, label[ i ], amount[ i ], 1, 1));
    }
  }

  let net_salary = jQuery('#net_salary').val();
  if (net_salary === '') {
    net_salary = 0;
  } else {
    net_salary = parseFloat(jQuery('#net_salary').val());
  }

  let total_deduction = jQuery('#total_deduction').val();
  if (total_deduction === '') {
    total_deduction = 0;
  } else {
    total_deduction = parseFloat(jQuery('#total_deduction').val());
  }

  if (removeAmount === '' || removeAmount === 0) {
    removeAmount = 0;
  } else {
    removeAmount = parseFloat(removeAmount);
  }

  let equ = net_salary + removeAmount;
  jQuery('#net_salary').val(equ);

  let equfg = total_deduction - removeAmount;
  jQuery('#total_deduction').val(equfg);
}

function formHtmlData(type, id, label, amount, add, remove) {
  let langLabel = '';
  let langValue = '';
  if (type === 'allowances') {
    langLabel = salarytemplate_allowances_label;
    langValue = salarytemplate_allowances_value;
  } else {
    langLabel = salarytemplate_deductions_label;
    langValue = salarytemplate_deductions_value;
  }

  let button = '';
  if (add === 1 && remove === 1) {
    button = '<button type="button" class="btn btn-danger btn-xs salary-btn salary-btn-' + type + '-remove" id="' + id + '" onclick="remove' + capitalize(type) + '(this)"><i class="fa fa-trash"></i></button><button type="button" class="btn btn-success btn-xs salary-btn salary-btn-' + type + '-add" id="salary-btn-' + type + '-add" onclick="add' + capitalize(type) + '()"><i class="fa fa-plus"></i></button>';
  } else if (remove === 1) {
    button = '<button type="button" class="btn btn-danger btn-xs salary-btn salary-btn-' + type + '-remove" id="' + id + '" onclick="remove' + capitalize(type) + '(this)"><i class="fa fa-trash"></i></button>';
  } else if (add === 1) {
    button = '<button type="button" class="btn btn-success btn-xs salary-btn salary-btn-' + type + '-add" id="salary-btn-' + type + '-add" onclick="add' + capitalize(type) + '()"><i class="fa fa-plus"></i></button>';
  }

  let html = '<div class="form-group row ' + type + 'field" ><div class="col-sm-5"><input type="text" class="form-control" id="' + type + 'label' + id + '" name="' + type + 'label' + id + '" value="' + label + '" placeholder="' + langLabel + '"></div><div class="col-sm-5"><input type="text" class="form-control ' + type + 'amount" id="' + type + 'amount' + id + '" name="' + type + 'amount' + id + '" value="' + amount + '" placeholder="' + langValue + '"></div><div class="col-sm-2" >' + button + '</div><span class="col-sm-12 errorpoint' + type + '" id="' + type + 'error' + id + '"></span></div>';
  return html;
}

function capitalize(s) {
  return s.toLowerCase().replace(/\b./g, function (a) { return a.toUpperCase(); });
}











