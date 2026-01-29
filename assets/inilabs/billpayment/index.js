
jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery(document).on('click', '#searchuhID', function() {
    let uhid = jQuery('#uhid').val();
    if(uhid > 0) {
      jQuery.post( THEMEBASEURL+'billpayment/getuhid', { [CSRFNAME] : CSRFHASH }, function(data) {
        window.location = THEMEBASEURL+'billpayment/index/'+displayID+'/'+uhid;
      });
    }
  });
});

jQuery(document).ready(function() {
  'use strict';
  let totalPayment    = parseFloat(0);
  let givenAmount     = jQuery('#amount').val();

  jQuery('.tableBody').each(function(index, value) {
    let amount = jQuery(this).children().eq(2).text();
    if(givenAmount === null || givenAmount === '' || givenAmount === 0) {
      givenAmount = 0;
    }
    amount      = parseFloat(amount);
    givenAmount = parseFloat(givenAmount);
    if(givenAmount > amount) {
      jQuery(this).children().eq(3).html(parseFloat(amount).toFixed(2));
      givenAmount = (givenAmount - amount);
    } else {
      jQuery(this).children().eq(3).html(parseFloat(givenAmount).toFixed(2));
      givenAmount = 0;
    }

    let payment   = jQuery(this).children().eq(3).text();
    payment    = parseFloat(payment);
    totalPayment = (totalPayment + payment);
  });

  jQuery('.tableFoot tr').children().eq(2).html(parseFloat(totalPayment).toFixed(2));
});

jQuery(document).on('keyup', '#amount', function() {
  let totalPayment    = parseFloat(0);
  let givenAmount     = jQuery(this).val();
  jQuery('.tableBody').each(function(index, value) {
    let amount = jQuery(this).children().eq(2).text();
    if(givenAmount === null || givenAmount === '' || givenAmount === 0) {
      givenAmount = 0;
    }
    amount      = parseFloat(amount);
    givenAmount = parseFloat(givenAmount);
    if(givenAmount > amount) {
      jQuery(this).children().eq(3).html(parseFloat(amount).toFixed(2));
      givenAmount = (givenAmount - amount);
    } else {
      jQuery(this).children().eq(3).html(parseFloat(givenAmount).toFixed(2));
      givenAmount = 0;
    }

    let payment   = jQuery(this).children().eq(3).text();
    payment    = parseFloat(payment);
    totalPayment = (totalPayment + payment);
  });
  jQuery('.tableFoot tr').children().eq(2).html(parseFloat(totalPayment).toFixed(2));
});
