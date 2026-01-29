
jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery('.datepicker').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy',
    todayHighlight : true,
  });

  jQuery(document).on('change', '.numberofbag', function() {
    let numberOfBag = jQuery(this).val();
    let bagNolabel  = '';
    let bagNoname   = '';
    let setValue    = '';
    let errorLabel  = '';
    let errorClass1 = '';
    let errorClass2 = '';
    jQuery('#bagNo').empty();
    for (let i = 1; i <= numberOfBag; i++) {
      if(i === 1) {
        bagNolabel  = bagNoLang1;
        bagNoname   = 'bagNo1';
      } else if(i === 2) {
        bagNolabel  = bagNoLang2;
        bagNoname   = 'bagNo2';
      } else if(i === 3) {
        bagNolabel  = bagNoLang3;
        bagNoname   = 'bagNo3';
      }
      jQuery('#bagNo').append(inputfield(bagNolabel, bagNoname, setValue, errorLabel, errorClass1, errorClass2));
    }
  });

  jQuery('#bagNo').empty();
  let bagNo1 = bagNoLang1;
  let bagNo2 = bagNoLang2;
  let bagNo3 = bagNoLang3;

  let bagNolabel  = '';
  let bagNoname   = '';
  let setValue    ='';
  let errorLabel  ='';
  let errorClass1 ='';
  let errorClass2 ='';

  let numberOfBag = myNumberOfBag;
  if(numberOfBag > 0) {
    for (let i = 1; i <= numberOfBag; i++) {
      if(i === 1) {
        bagNolabel  = bagNo1;
        bagNoname   = 'bagNo1';
        setValue    = setValue1;
        errorLabel  = errorLabel1;
        errorClass1 = errorClass11;
        errorClass2 = errorClass21;
      } else if(i === 2) {
        bagNolabel  = bagNo2;
        bagNoname   = 'bagNo2';
        setValue    = setValue2;
        errorLabel  = errorLabel2;
        errorClass1 = errorClass12;
        errorClass2 = errorClass22;
      } else if(i === 3) {
        bagNolabel  = bagNo3;
        bagNoname   = 'bagNo3';
        setValue    = setValue3;
        errorLabel  = errorLabel3;
        errorClass1 = errorClass13;
        errorClass2 = errorClass23;
      }
      jQuery('#bagNo').append(inputfield(bagNolabel, bagNoname, setValue, errorLabel, errorClass1, errorClass2));
    }
  } else {
    jQuery('#bagNo').empty();
  }

  function inputfield(label, name, setValue= '', errorLabel= '', errorClass1= '', errorClass2= '')
  {
    let retfield = '';
    retfield += '<div class="form-group '+errorClass1+'">';
    retfield += '<label class="control-label" for="'+name+'"> '+label+' <span class="text-danger">*</span></label>';
    retfield += '<input type="text" class="form-control '+errorClass2+'" id="'+name+'" name="'+name+'"  value="'+setValue+'">';
    retfield += '<span>'+errorLabel+'</span>';
    retfield += '</div>';
    return retfield;
  }

  jQuery(document).on('click', '.viewModalBtn', function() {
    let blooddonorID = jQuery(this).attr('id');
    if(blooddonorID > 0 ) {
      jQuery.ajax({
        type: 'POST',
        url: THEMEBASEURL+'blooddonor/view',
        data: { 'blooddonorID' : blooddonorID, [CSRFNAME] : CSRFHASH},
        dataType: 'html',
        success: function (data) {
          jQuery('#viewModal .modal-body').html(data);
        }
      });
    }
  });
});


