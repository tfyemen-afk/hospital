jQuery(document).ready(function () {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datepicker').datepicker({
    format : 'dd-mm-yyyy',
    todayHighlight : true,
    autoclose : true,
  });
  jQuery('.datepicker').datepicker('setDate', new Date());

  jQuery(document).on('change', '.file-upload-input', function () {
    if (this.files.length > 0) {
      jQuery('.label-text-hide').text(this.files[ 0 ].name);
    } else {
      jQuery('.label-text-hide').text('Choose file');
    }
  });

  jQuery('.paymentDiv').hide();
  jQuery(document).on('change', '#payment_status', function () {
    let payment_status = jQuery(this).val();
    if ((payment_status === '1') || (payment_status === '2')) {
      jQuery('.paymentDiv').show('slow');
    } else {
      jQuery('.paymentDiv').hide('slow');
    }
  });

  jQuery('#payment_amount').on('keyup', function () {
    let productsalepaidamount = toFixedVal(jQuery(this).val());
    if (dotAndNumber(productsalepaidamount)) {
      if (productsalepaidamount !== '' && productsalepaidamount !== null) {
        if (floatChecker(productsalepaidamount)) {
          if (productsalepaidamount.length > 15) {
            jQuery(this).val(globalsubtotal);
          } else {
            if (productsalepaidamount > globalsubtotal) {
              jQuery(this).val(globalsubtotal);
            }
          }
        }
      }
    } else {
      let productsalepaidamount = parseSentenceForNumber(jQuery(this).val());
      jQuery(this).val(productsalepaidamount);
    }
  });

  jQuery(document).on('click', '#addMedicineButton', function (event) {
    event.preventDefault();
    let error = 0;
    let field = {
      'medicinewarehouseID' : jQuery('#medicinewarehouseID').val(),
      'purchase_referenceno' : jQuery('#purchase_referenceno').val(),
      'medicinepurchasedate' : jQuery('#medicinepurchasedate').val(),
      'payment_status' : jQuery('#payment_status').val(),
      'reference_no' : jQuery('#purchasepaid_referenceno').val(),
      'payment_amount' : jQuery('#payment_amount').val(),
      'payment_method' : jQuery('#purchasepaid_payment_method').val(),
      'medicinepurchasedescription' : jQuery('#medicinepurchasedescription').val(),
    };

    if (field[ 'medicinewarehouseID' ] === '0') {
      jQuery('#medicinewarehouseID').parent().addClass('text-danger');
      jQuery('#medicinewarehouseID').parent().children('.select2-container').addClass('is-invalid');
      jQuery('#medicinewarehouseID-error').text('The warehouse field is required.');
      error++;
    } else {
      jQuery('#medicinewarehouseID').parent().removeClass('text-danger');
      jQuery('#medicinewarehouseID').parent().children('.select2-container').removeClass('is-invalid');
      jQuery('#medicinewarehouseID-error').text('');
    }

    if (field[ 'purchase_referenceno' ] === '') {
      jQuery('#purchase_referenceno').parent().addClass('text-danger');
      jQuery('#purchase_referenceno').addClass('is-invalid');
      jQuery('#purchase_referenceno-error').text('The reference no field is required.');
      error++;
    } else {
      jQuery('#purchase_referenceno').parent().removeClass('text-danger');
      jQuery('#purchase_referenceno').removeClass('is-invalid');
      jQuery('#purchase_referenceno-error').text('');
    }

    if (field[ 'medicinepurchasedate' ] === '') {
      jQuery('#medicinepurchasedate').parent().addClass('text-danger');
      jQuery('#medicinepurchasedate').addClass('is-invalid');
      jQuery('#medicinepurchasedate-error').text('The date field is required.');
      error++;
    } else {
      jQuery('#medicinepurchasedate').parent().removeClass('text-danger');
      jQuery('#medicinepurchasedate').removeClass('is-invalid');
      jQuery('#medicinepurchasedate-error').text('');
    }

    if (field[ 'payment_status' ] === 'none') {
      jQuery('#payment_status').parent().addClass('text-danger');
      jQuery('#payment_status').parent().children('.select2-container').addClass('is-invalid');
      jQuery('#payment_status-error').text('The payment status field is required.');
      error++;
    } else {
      if (field[ 'payment_status' ] === '0' || field[ 'payment_status' ] === '1' || field[ 'payment_status' ] === '2') {
        jQuery('#payment_status').parent().removeClass('text-danger');
        jQuery('#payment_status').parent().children('.select2-container').removeClass('is-invalid');
        jQuery('#payment_status-error').text('');
      }

      if (field[ 'payment_status' ] > 0) {
        if (field[ 'payment_amount' ] === '') {
          jQuery('#payment_amount').parent().addClass('text-danger');
          jQuery('#payment_amount').addClass('is-invalid');
          jQuery('#payment_amount-error').text('The payment amount field is required.');
          error++;
        } else {
          if (field[ 'payment_amount' ] <= 0) {
            jQuery('#payment_amount').parent().addClass('text-danger');
            jQuery('#payment_amount').addClass('is-invalid');
            jQuery('#payment_amount-error').text('The Amount field must contain a number greater than 0.');
            error++;
          } else {
            jQuery('#payment_amount').parent().removeClass('text-danger');
            jQuery('#payment_amount').removeClass('is-invalid');
            jQuery('#payment_amount-error').text('');
          }
        }

        if (field[ 'payment_method' ] === '0') {
          jQuery('#purchasepaid_payment_method').parent().addClass('text-danger');
          jQuery('#purchasepaid_payment_method').parent().children('.select2-container').addClass('is-invalid');
          jQuery('#purchasepaid_payment_method-error').text('The payment method field is required.');
          error++;
        } else {
          jQuery('#purchasepaid_payment_method').parent().removeClass('text-danger');
          jQuery('#purchasepaid_payment_method').parent().children('.select2-container').removeClass('is-invalid');
          jQuery('#purchasepaid_payment_method-error').text('');
        }
      }
    }

    let batchCheck = [];
    let batchObj = [];
    jQuery('#medicineList tr').each(function (index, value) {

      if (jQuery(this).children().eq(2).children().val() === '') {
        let batchID = jQuery(this).children().eq(2).children().attr('id');
        jQuery('#' + batchID).addClass('border border-danger');
        error++;
      } else {
        let batchID = jQuery(this).children().eq(2).children().attr('id');
        jQuery('#' + batchID).removeClass('border border-danger');
      }

      if (jQuery(this).children().eq(3).children().val() === '') {
        let expire_date = jQuery(this).children().eq(3).children().attr('id');
        jQuery('#' + expire_date).addClass('border border-danger');
        error++;
      } else {
        let expire_date = jQuery(this).children().eq(3).children().attr('id');
        jQuery('#' + expire_date).removeClass('border border-danger');
      }

      if (jQuery(this).children().eq(4).children().val() === '') {
        let unit_price = jQuery(this).children().eq(4).children().attr('id');
        jQuery('#' + unit_price).addClass('border border-danger');
        error++;
      } else {
        let unit_price = jQuery(this).children().eq(4).children().attr('id');
        jQuery('#' + unit_price).removeClass('border border-danger');
      }

      if (jQuery(this).children().eq(5).children().val() === '') {
        let quantity = jQuery(this).children().eq(5).children().attr('id');
        jQuery('#' + quantity).addClass('border border-danger');
        error++;
      } else {
        let quantity = jQuery(this).children().eq(5).children().attr('id');
        jQuery('#' + quantity).removeClass('border border-danger');
      }

      let medicineid = jQuery(this).attr('medicineid');
      let medicineitemid = jQuery(this).attr('medicineitemid');
      let batchrandID = jQuery(this).children().eq(2).children().attr('id');
      let batchvalue = jQuery(this).children().eq(2).children().val();
      let medicine_batchvalue = medicineid + '-' + batchvalue;

      let batchObjArray = {};
      if (batchCheck.indexOf(medicine_batchvalue) == -1) {
        batchCheck.push(medicine_batchvalue);
        if (batchvalue !== '') {
          batchObjArray[ 'batchrandID' ] = batchrandID;
          batchObjArray[ 'batchvalue' ] = batchvalue;
          batchObjArray[ 'medicineid' ] = medicineid;
          batchObjArray[ 'medicineitemid' ] = medicineitemid;

          batchObj.push(batchObjArray);
        }
      } else {
        jQuery('#' + batchrandID).addClass('border border-danger');
        error++;
      }
    });

    let medicineitems = jQuery('tr[id^=tr_]').map(function (index, value) {
      return {
        'medicineid' : jQuery(this).attr('medicineid'),
        'medicineitemid' : jQuery(this).attr('medicineitemid'),
        'batchID' : jQuery(this).children().eq(2).children().val(),
        'expire_date' : jQuery(this).children().eq(3).children().val(),
        'unit_price' : jQuery(this).children().eq(4).children().val(),
        'quantity' : jQuery(this).children().eq(5).children().val()
      };
    }).get();

    if (typeof medicineitems == 'undefined' || medicineitems.length <= 0) {
      error++;
      toastr[ 'error' ]('The medicine item is required.');
      toastr.options = {
        'closeButton' : true,
        'debug' : false,
        'newestOnTop' : false,
        'progressBar' : false,
        'positionClass' : 'toast-top-right',
        'preventDuplicates' : false,
        'onclick' : null,
        'showDuration' : '500',
        'hideDuration' : '500',
        'timeOut' : '5000',
        'extendedTimeOut' : '1000',
        'showEasing' : 'swing',
        'hideEasing' : 'linear',
        'showMethod' : 'fadeIn',
        'hideMethod' : 'fadeOut'
      }
    }
    medicineitems = JSON.stringify(medicineitems);
    batchObj = JSON.stringify(batchObj);

    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'medicinepurchase/checkmedcinebatch',
      data : {'data' : batchObj, [ CSRFNAME ] : CSRFHASH},
      dataType : 'html',
      success : function (data) {
        let response = JSON.parse(data);
        if (response.status) {
          error++;
          let responseData = response.data;
          jQuery.each(responseData, function (index, value) {
            jQuery('#' + index).addClass('border border-danger');
          });
        }

        if (error === 0) {
          jQuery(this).attr('disabled', 'disabled');
          let formData = new FormData(jQuery('#medicinePurchaseDataForm')[ 0 ]);
          formData.append('medicineitems', medicineitems);
          formData.append([ CSRFNAME ], CSRFHASH);
          makingPostDataPreviousofAjaxCall(formData);
        }
      },
    });
  });

  function makingPostDataPreviousofAjaxCall(field) {
    let passData = field;
    ajaxCall(passData);
  }

  function ajaxCall(passData) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'medicinepurchase/savemedicinepurchase',
      data : passData,
      async : true,
      dataType : 'html',
      success : function (data) {
        let response = JSON.parse(data);
        errorLoader(response);
      },
      cache : false,
      contentType : false,
      processData : false
    });
  }

  function errorLoader(response) {
    if (response.status) {
      window.location = THEMEBASEURL + 'medicinepurchase/index';
    } else {
      jQuery('#addMedicineButton').removeAttr('disabled');
      toastr[ 'error' ](response.message);
      toastr.options = {
        'closeButton' : true,
        'debug' : false,
        'newestOnTop' : false,
        'progressBar' : false,
        'positionClass' : 'toast-top-right',
        'preventDuplicates' : false,
        'onclick' : null,
        'showDuration' : '500',
        'hideDuration' : '500',
        'timeOut' : '5000',
        'extendedTimeOut' : '1000',
        'showEasing' : 'swing',
        'hideEasing' : 'linear',
        'showMethod' : 'fadeIn',
        'hideMethod' : 'fadeOut'
      }
    }
  }


  jQuery(document).on('change', '#medicinecategoryID', function () {
    let medicinecategoryID = jQuery(this).val();
    if (medicinecategoryID > 0) {
      jQuery.ajax({
        url : THEMEBASEURL + 'medicinepurchase/get_medicine',
        type : 'POST',
        dataType : 'html',
        data : {'medicinecategoryID' : medicinecategoryID, [ CSRFNAME ] : CSRFHASH},
        success : function (data) {
          jQuery('#medicineID').html(data);
        }
      });
    }
  });

  jQuery('#medicineID').change(function () {
    let medicineID = jQuery(this).val();
    let medicineName = jQuery(this).find(':selected').text();
    if (medicineID > 0) {
      let appendData = medicineItemDesign(medicineID, medicineName);
      jQuery('#medicineList').append(appendData);
      jQuery('#medicineList').find('.datepicker').datepicker({
        format : 'dd-mm-yyyy',
        startDate : my_set_start_expire_date,
        autoclose : true
      });
    }
  });

  function medicineItemDesign(medicineID, medicineName) {
    let medicineobj = myMedicineobj;
    let randID = getRandomInt();
    let lastTdNumber = '';
    if (jQuery('#medicineList tr:last').text() === '') {
      lastTdNumber = 0;
    } else {
      lastTdNumber = jQuery('#medicineList tr:last td:eq(0)').text();
    }

    let medicineobjinfo = '';
    if (typeof(medicineobj) === 'object') {
      if (typeof(medicineobj[ medicineID ]) === 'object') {
        medicineobjinfo = medicineobj[ medicineID ];
      } else {
        medicineobjinfo = {'medicineID' : medicineID, 'buying_price' : '0'};
      }
    }

    lastTdNumber = parseInt(lastTdNumber);
    lastTdNumber++;

    let text = '<tr id="tr_' + randID + '" medicineid="' + medicineID + '" medicineitemid="0">';
    text += '<td>';
    text += lastTdNumber;
    text += '</td>';

    text += '<td>';
    text += medicineName;
    text += '</td>';

    text += '<td>';
    text += ('<input type="text" class="form-control change-medicinebatchID" id="medicinebatchID_' + randID + '">');
    text += '</td>';

    text += '<td>';
    text += ('<input type="text" class="form-control change-medicineexpiredate datepicker" id="medicineexpiredate_' + randID + '">');
    text += '</td>';

    text += '<td>';
    text += ('<input type="text" class="form-control change-medicineprice" id="medicineprice_' + randID + '" value="' + medicineobjinfo.buying_price + '"data-medicineprice-id="' + randID + '">');
    text += '</td>';


    text += '<td>';
    text += ('<input type="text" class="form-control change-medicinequantity" id="medicinequantity_' + randID + '" data-medicinequantity-id="' + randID + '">');
    text += '</td>';

    text += '<td id="medicinetotal_' + randID + '">';
    text += '0.00';
    text += '</td>';

    text += '<td>';
    text += ('<a href="#" class="btn btn-danger btn-sm deleteBtn" id="medicineaction_' + randID + '" data-medicineaction-id="' + randID + '"><i class="fa fa-trash-o"></i></a>');
    text += '</td>';
    text += '</tr>';

    return text;
  }

  let globalsubtotal = 0;

  function totalInfo() {
    let totalQuantity = 0;
    let totalSubtotal = 0;
    jQuery('#medicineList tr').each(function (index, value) {
      if (jQuery(this).children().eq(5).children().val() !== '' && jQuery(this).children().eq(5).children().val() !== null) {
        let quantity = parseFloat(jQuery(this).children().eq(5).children().val());
        let subtotal = parseFloat(jQuery(this).children().eq(6).text().replace(/,/gi, ''));
        totalQuantity += quantity;
        totalSubtotal += subtotal;
      }
    });
    globalsubtotal = totalSubtotal;

    jQuery('#totalQuantity').text(currencyConvert(totalQuantity));
    jQuery('#totalSubtotal').text(currencyConvert(totalSubtotal));
  }

  jQuery(document).on('keyup', '.change-medicineprice', function () {
    let medicinePrice = toFixedVal(jQuery(this).val());
    let medicinePriceID = jQuery(this).attr('data-medicineprice-id');
    let medicineQuantity = toFixedVal(jQuery('#medicinequantity_' + medicinePriceID).val());

    if (dotAndNumber(medicinePrice)) {
      if (medicinePrice.length > 15) {
        medicinePrice = lenChecker(medicinePrice, 15);
        jQuery(this).val(medicinePrice);
      }

      if ((medicinePrice !== '' && medicinePrice !== null) && (medicineQuantity !== '' && medicineQuantity !== null)) {
        if (floatChecker(medicinePrice)) {
          if (medicinePrice.length > 15) {
            medicinePrice = lenChecker(medicinePrice, 15);
            jQuery(this).val(medicinePrice);
            jQuery('#medicinetotal_' + medicinePriceID).text(currencyConvert(medicinePrice * medicineQuantity));
            totalInfo();
          } else {
            jQuery('#medicinetotal_' + medicinePriceID).text(currencyConvert(medicinePrice * medicineQuantity));
            totalInfo();
          }
        }
      } else {
        jQuery('#medicinetotal_' + medicinePriceID).text('0.00');
        totalInfo();
      }
    } else {
      let medicinePrice = parseSentenceForNumber(jQuery(this).val());
      jQuery(this).val(medicinePrice);
    }
  });

  jQuery(document).on('keyup', '.change-medicinequantity', function () {
    let medicinequantity = toFixedVal(jQuery(this).val());
    let medicinequantityID = jQuery(this).attr('data-medicinequantity-id');
    let medicinePrice = toFixedVal(jQuery('#medicineprice_' + medicinequantityID).val());

    if (dotAndNumber(medicinequantity)) {
      if (medicinequantity.length > 15) {
        medicinequantity = lenChecker(medicinequantity, 15);
        jQuery(this).val(medicinequantity);
      }

      if ((medicinequantity !== '' && medicinequantity !== null) && (medicinePrice !== '' && medicinePrice !== null)) {
        if (floatChecker(medicinequantity)) {
          if (medicinequantity.length > 15) {
            medicinequantity = lenChecker(medicinequantity, 15);
            jQuery(this).val(medicinequantity);
            jQuery('#medicinetotal_' + medicinequantityID).text(currencyConvert(medicinePrice * medicinequantity));
            totalInfo();
          } else {
            jQuery('#medicinetotal_' + medicinequantityID).text(currencyConvert(medicinePrice * medicinequantity));
            totalInfo();
          }
        }
      } else {
        jQuery('#medicinetotal_' + medicinequantityID).text('0.00');
        totalInfo();
      }
    } else {
      let medicinequantity = parseSentenceForNumber(jQuery(this).val());
      jQuery(this).val(medicinequantity);
    }
  });

  jQuery(document).on('click', '.deleteBtn', function (e) {
    e.preventDefault();
    let medicineItemID = jQuery(this).attr('data-medicineaction-id');
    jQuery('#tr_' + medicineItemID).remove();

    let i = 1;
    jQuery('#medicineList tr').each(function (index, value) {
      jQuery(this).children().eq(0).text(i);
      i++;
    });
    totalInfo();
  });

  function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  }

  function floatChecker(value) {
    let val = value;
    if (isNumeric(val)) {
      return true;
    } else {
      return false;
    }
  }

  function dotAndNumber(data) {
    let retArray = [];
    let fltFlag = true;
    if (data.length > 0) {
      for (let i = 0; i <= (data.length - 1); i++) {
        if (i === 0 && data.charAt(i) === '.') {
          fltFlag = false;
          retArray.push(true);
        } else {
          if (data.charAt(i) === '.' && fltFlag === true) {
            retArray.push(true);
            fltFlag = false;
          } else {
            if (isNumeric(data.charAt(i))) {
              retArray.push(true);
            } else {
              retArray.push(false);
            }
          }

        }
      }
    }

    if (jQuery.inArray(false, retArray) == -1) {
      return true;
    }
    return false;
  }

  function parseSentenceForNumber(sentence) {
    let matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
    return matches && matches[ 0 ] || null;
  }

  function getRandomInt() {
    return Math.floor(Math.random() * Math.floor(9999999999999999));
  }

  function currencyConvert(data) {
    return data.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
  }

  function toFixedVal(x) {
    if (Math.abs(x) < 1.0) {
      let e = parseFloat(x.toString().split('e-')[ 1 ]);
      if (e) {
        x *= Math.pow(10, e - 1);
        x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
      }
    } else {
      let e = parseFloat(x.toString().split('+')[ 1 ]);
      if (e > 20) {
        e -= 20;
        x /= Math.pow(10, e);
        x += (new Array(e + 1)).join('0');
      }
    }
    return x;
  }

  function lenChecker(data, len) {
    let retdata = 0;
    let lencount = 0;
    data = toFixedVal(data);
    if (data.length > len) {
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