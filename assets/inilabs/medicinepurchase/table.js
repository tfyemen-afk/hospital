jQuery(document).ready(function() {
  'use strict';
  let globaldueAmount          = 0;
  let globalmedicinepurchaseID = 0;

  jQuery(document).on('click', '.getPurchaseInfo', function() {
    let medicinepurchaseID   = jQuery(this).attr('id');
    globalmedicinepurchaseID = medicinepurchaseID;
    if(medicinepurchaseID > 0) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'medicinepurchase/getpurchaseinfo',
        data: {'medicinepurchaseID' : medicinepurchaseID, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function(data) {
          let response = JSON.parse(data);
          jQuery('#medicinepurchasepaidamount').val('');
          if(response.status == true) {
            jQuery('#medicinepurchasepaidamount').val(response.dueamount);
            globaldueAmount = parseFloat(response.dueamount);
          }
        }
      });
    }
  });

  jQuery(document).on('click', '.getPurchasePaidInfo', function() {
    let medicinepurchaseID  = jQuery(this).attr('id');
    if(medicinepurchaseID > 0) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'medicinepurchase/getpurchasepaidinfo',
        data: {'medicinepurchaseID' : medicinepurchaseID, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function(data) {
          let response = JSON.parse(data);
          jQuery('#viewaymentView').html('');
          if(response.status == true) {
            jQuery('#viewaymentView').html(response.view);
          }
        }
      });
    }
  });

  jQuery(document).on('keyup', '#medicinepurchasepaidamount', function() {
    let medicinepurchasepaidamount =  jQuery(this).val();
    if(dotAndNumber(medicinepurchasepaidamount)) {
      if(medicinepurchasepaidamount !== '' && medicinepurchasepaidamount !== null) {
        if(floatChecker(medicinepurchasepaidamount)) {
          if(medicinepurchasepaidamount.length > 15) {
            medicinepurchasepaidamount = lenChecker(medicinepurchasepaidamount);
            jQuery(this).val(medicinepurchasepaidamount);

            if(medicinepurchasepaidamount > globaldueAmount) {
              jQuery(this).val(globaldueAmount);
            }
          } else {
            if(medicinepurchasepaidamount > globaldueAmount) {
              jQuery(this).val(globaldueAmount);
            }
          }
        }
      }
    } else {
      let medicinepurchasepaidamount = parseSentenceForNumber(jQuery(this).val());
      jQuery(this).val(medicinepurchasepaidamount);
    }
  });

  jQuery(document).on('click', '.getPaymentInfo', function() {
    let error = 0;
    let field = {
      'payment_date'   : jQuery('#payment_date').val(),
      'reference_no'   : jQuery('#reference_no').val(),
      'payment_method' : jQuery('#payment_method').val(),
      'medicinepurchasepaidamount' : jQuery('#medicinepurchasepaidamount').val(),
    };

    if (field['payment_date'] === '') {
      jQuery('#payment_date').parent().addClass('text-danger');
      jQuery('#payment_date').addClass('is-invalid');
      error++;
    } else {
      jQuery('#payment_date').parent().removeClass('text-danger');
      jQuery('#payment_date').removeClass('is-invalid');
    }

    if (field['payment_method'] === '0') {
      jQuery('#payment_method').parent().addClass('text-danger');
      jQuery('#payment_method').parent().children('.select2-container').addClass('is-invalid');
      error++;
    } else {
      jQuery('#payment_method').parent().removeClass('text-danger');
      jQuery('#payment_method').parent().children('.select2-container').removeClass('is-invalid');
    }

    if (field['medicinepurchasepaidamount'] === '' || field['medicinepurchasepaidamount'] <= 0) {
      jQuery('#medicinepurchasepaidamount').parent().addClass('text-danger');
      jQuery('#medicinepurchasepaidamount').addClass('is-invalid');
      error++;
    } else {
      jQuery('#medicinepurchasepaidamount').parent().removeClass('text-danger');
      jQuery('#medicinepurchasepaidamount').removeClass('is-invalid');
    }

    if(error === 0) {
      jQuery(this).attr('disabled', 'disabled');
      let formData = new FormData(jQuery('#medicinePurchasePaymentData')[0]);
      formData.append('medicinepurchaseID', globalmedicinepurchaseID);
      formData.append([CSRFNAME], CSRFHASH);
      makingPostDataPreviousofAjaxCallPayment(formData);
    }
  });

  function makingPostDataPreviousofAjaxCallPayment(field) {
    let passData = field;
    ajaxCallPayment(passData);
  }

  function ajaxCallPayment(passData) {
    jQuery.ajax({
      type: 'POST',
      url: THEMEBASEURL+'medicinepurchase/savemedicinepurchasepayment',
      data: passData,
      async: true,
      dataType: 'html',
      success: function(data) {
        let response = JSON.parse(data);
        errorLoaderPayment(response);
      },
      cache: false,
      contentType: false,
      processData: false
    });
  }

  function errorLoaderPayment(response) {
    if(response.status) {
      window.location = THEMEBASEURL+'medicinepurchase/index';
    } else {
      jQuery('.getPaymentInfo').removeAttr('disabled');
      jQuery.each(response, function(index, val) {
        if(index !== 'status') {
          toastr['error'](val);
          toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': false,
            'positionClass': 'toast-top-right',
            'preventDuplicates': false,
            'onclick': null,
            'showDuration': '500',
            'hideDuration': '500',
            'timeOut': '5000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut'
          }
        }
      });
    }
  }

  function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  }

  function floatChecker(value) {
    let val = value;
    if(isNumeric(val)) {
      return true;
    } else {
      return false;
    }
  }

  function parseSentenceForNumber(sentence) {
    let matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
    return matches && matches[0] || null;
  }

  function dotAndNumber(data) {
    let retArray = [];
    let fltFlag = true;
    if(data.length > 0) {
      for(let i = 0; i <= (data.length-1); i++) {
        if(i === 0 && data.charAt(i) === '.') {
          fltFlag = false;
          retArray.push(true);
        } else {
          if(data.charAt(i) === '.' && fltFlag === true) {
            retArray.push(true);
            fltFlag = false;
          } else {
            if(isNumeric(data.charAt(i))) {
              retArray.push(true);
            } else {
              retArray.push(false);
            }
          }

        }
      }
    }

    if(jQuery.inArray(false, retArray) ==  -1) {
      return true;
    }
    return false;
  }

  function toFixedVal(x) {
    if (Math.abs(x) < 1.0) {
      let e = parseFloat(x.toString().split('e-')[1]);
      if (e) {
        x *= Math.pow(10,e-1);
        x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
      }
    } else {
      let e = parseFloat(x.toString().split('+')[1]);
      if (e > 20) {
        e -= 20;
        x /= Math.pow(10,e);
        x += (new Array(e+1)).join('0');
      }
    }
    return x;
  }

  function lenChecker(data, len) {
    let retdata = 0;
    let lencount = 0;
    data = toFixedVal(data);
    if(data.length > len) {
      lencount = (data.length - len);
      data = data.toString();
      data = data.slice(0, -lencount);
      retdata = parseFloat(data);
    } else {
      retdata = parseFloat(data);
    }

    return toFixedVal(retdata);
  }
});