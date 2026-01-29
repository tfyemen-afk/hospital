
jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();

  jQuery(document).on('click', '#billUpdate', function(event) {
    event.preventDefault();
    let error = 0;
    let patientID     = jQuery('#uhid').val();

    if(patientID === 0) {
      error++;
      jQuery('#uhid').parent().addClass('text-danger');
      jQuery('#uhid').parent().children('.select2-container').addClass('is-invalid');
      jQuery('#uhid-error').text('The UHID field is required.');
    } else {
      jQuery('#uhid').parent().removeClass('text-danger');
      jQuery('#uhid').parent().children('.select2-container').removeClass('is-invalid');
      jQuery('#uhid-error').text('');
    }

    let billitems = jQuery('#billList tr').map(function() {
      return {
        'billlabel' : jQuery(this).attr('billlabelid'),
        'amount'    : jQuery(this).children().eq(3).html().replace(/\s/g, ''),
        'discount'  : jQuery(this).children().eq(2).children().val(),
      }
    }).get();

    if (typeof billitems === 'undefined' || billitems.length <= 0) {
      error++;
      toastr['error']('The bill item is required.');
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

    if(error === 0) {
      jQuery(this).attr('disabled', 'disabled');
      let formData = new FormData(jQuery('#billdata')[0]);
      formData.append('billitems', JSON.stringify(billitems));
      formData.append('billID', billID);
      formData.append([CSRFNAME], CSRFHASH);
      makingPostDataPreviousofAjaxCall(formData);
    }
  });

  function makingPostDataPreviousofAjaxCall(field)
  {
    let passData = field;
    ajaxCall(passData);
  }

  function ajaxCall(passData)
  {
    jQuery.ajax({
      type: 'POST',
      url: THEMEBASEURL+'bill/savebillforedit',
      data: passData,
      async: true,
      dataType: 'html',
      success: function(data) {
        let response = JSON.parse(data);
        errorLoader(response);
      },
      cache: false,
      contentType: false,
      processData: false
    });
  }

  function errorLoader(response)
  {
    if(response.status) {
      if(viewPermission) {
        window.location = THEMEBASEURL+'bill/view/'+response.id+'/'+displayID;
      } else {
        window.location = THEMEBASEURL+'bill/index/'+displayID;
      }
    } else {
      jQuery('#billUpdate').removeAttr('disabled');
      toastr['error'](response.message);
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
  }

  jQuery(document).on('change', '#billcategoryID', function() {
    let billcategoryID = jQuery(this).val();
    if(billcategoryID === '0') {
      jQuery('#billlabelID').html('<option value="0">— '+bill_please_select+' —</option>');
    } else {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'bill/get_billlabel',
        data: {'billcategoryID': billcategoryID, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function(data) {
          jQuery('#billlabelID').html(data);
        }
      });
    }
  });

  jQuery(document).on('change', '#billlabelID', function() {
    let billlabelID = jQuery(this).val();
    if(billlabelID !== 0) {
      let billlabelText = jQuery(this).find(':selected').text();
      let appendData = productItemDesign(billlabelID, billlabelText);
      jQuery('#billList').append(appendData);
      totalInfo();
    }
  });

  jQuery(document).on('keyup mouseup', '.change-discount', function() {
    let discountID  = jQuery(this).attr('data-discountid');
    let discount    = toFixedVal(jQuery(this).val());
    let billamount  = toFixedVal(jQuery('#billtotal_'+discountID).text());

    if(discount > 100) {
      let lencount = (discount.length - 1);
      lencount = lencount.toString();
      discount = discount.slice(0, lencount);
      jQuery(this).val(discount);
    }

    if(dotAndNumber(discount)) {
      if(discount.length > 4) {
        discount = lenChecker(discount, 4);
        jQuery(this).val(discount);
      }

      if((discount !== '' && discount !== null) && (billamount !== '' && billamount !== null)) {
        if(floatChecker(discount)) {
          let discountAmount = ((discount / 100) * billamount);
          let billsubtotal   = (billamount - discountAmount);
          jQuery('#billsubtotal_'+discountID).text(currencyConvert(billsubtotal));
          totalInfo();
        }
      } else {
        if(billamount !== '' && billamount !== null) {
          billamount = parseFloat(billamount);
          jQuery('#billsubtotal_'+discountID).text(currencyConvert(billamount));
          totalInfo();
        } else {
          jQuery('#billsubtotal_'+discountID).text('0.00');
          totalInfo();
        }
      }
    } else {
      let discount = parseSentenceForNumber(discount);
      jQuery(this).val(discount);
      totalInfo();
    }
  });

  jQuery(document).on('click', '.deleteBtn', function(e) {
    e.preventDefault();
    let productItemID = jQuery(this).attr('data-productaction-id');
    jQuery('#tr_'+productItemID).remove();

    let i = 1;
    jQuery('#billList tr').each(function(index, value) {
      jQuery(this).children().eq(0).text(i);
      i++;
    });
    totalInfo();
  });

  function productItemDesign(productID, productText)
  {
    let productobj = billlabelsobj;
    let randID = getRandomInt();
    let lastTdNumber = 0;
    let productobjinfo = [];

    if(jQuery('#billList tr:last').text() === '') {
      lastTdNumber = 0;
    } else {
      lastTdNumber = jQuery('#billList tr:last td:eq(0)').text();
    }

    if(typeof(productobj) === 'object') {
      if(typeof(productobj[productID]) === 'object') {
        productobjinfo = productobj[productID];
      } else {
        productobjinfo = {'billlabelID' : productID, 'discount' : '0', 'amount' : '0'};
      }
    } else {
      productobjinfo = {'billlabelID' : productID, 'discount' : '0', 'amount' : '0'};
    }

    lastTdNumber = parseInt(lastTdNumber);
    lastTdNumber++;

    let maindiscount = ((productobjinfo.discount / 100) * productobjinfo.amount);
    let mainbilllabelamount    = (productobjinfo.amount - maindiscount);

    let text = '<tr id="tr_'+randID+'" billlabelid="'+productID+'">';
    text += '<td>';
    text += lastTdNumber;
    text += '</td>';

    text += '<td>';
    text += productText;
    text += '</td>';

    text += '<td>';
    text += ('<input type="number" min="0" max="100" class="form-control change-discount" id="discount_'+randID+'" value="'+productobjinfo.discount+'" data-discountid="'+randID+'">');
    text += '</td>';

    text += '<td id="billtotal_'+randID+'">';
    text += parseFloat(productobjinfo.amount);
    text += '</td>';

    text += '<td id="billsubtotal_'+randID+'">';
    text += currencyConvertWithOutComma(parseFloat(mainbilllabelamount));
    text += '</td>';

    text += '<td>';
    text += ('<a href="#" class="btn btn-danger btn-sm margin-delete deleteBtn" id="productaction_'+randID+'" data-productaction-id="'+randID+'"><i class="fa fa-trash-o"></i></a>');
    text += '</td>';
    text += '</tr>';
    return text;
  }

  function totalInfo()
  {
    let billtotal    = 0;
    let billsubtotal = 0;
    jQuery('#billList tr').each(function(index, value) {
      let billtotalval    = parseFloat(jQuery(this).children().eq(3).text().replace(/,/gi, ''));
      let billsubtotalval = parseFloat(jQuery(this).children().eq(4).text().replace(/,/gi, ''));
      billtotal      += billtotalval;
      billsubtotal   += billsubtotalval;
    });
    jQuery('#totalAmount').text(currencyConvert(billtotal));
    jQuery('#totalSubtotal').text(currencyConvert(billsubtotal));
  }

  function getRandomInt()
  {
    return Math.floor(Math.random() * Math.floor(9999999999999999));
  }

  function parseSentenceForNumber(sentence)
  {
    var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
    return matches && matches[0] || null;
  }

  function currencyConvert(data)
  {
    return data.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
  }

  function currencyConvertWithOutComma(data)
  {
    return data.toFixed(2);
  }

  function toFixedVal(x)
  {
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

  function dotAndNumber(data)
  {
    let retArray = [];
    let fltFlag = true;
    if(data.length > 0) {
      for(var i = 0; i <= (data.length-1); i++) {
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

  function isNumeric(n)
  {
    return !isNaN(parseFloat(n)) && isFinite(n);
  }

  function floatChecker(value)
  {
    let val = value;
    if(isNumeric(val)) {
      return true;
    } else {
      return false;
    }
  }

  function lenChecker(data, len)
  {
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