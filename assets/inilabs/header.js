function printDiv(divID) {
  'use strict';
  let divElements  = document.getElementById(divID).innerHTML;
  let oldPage      = document.body.innerHTML;
  document.body.innerHTML = "<html><head><title><?=jQueryprofile->name;?></title></head><body>" + divElements + "</body>";
  window.print();
  document.body.innerHTML = oldPage;
  window.location.reload();
}

function check_email(email) {
  'use strict';
  let status = false;
  let emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
  if (email.search(emailRegEx) === -1) {
    jQuery('#to_error').html('The To field must contain a valid email address').css('text-align', 'left').css('color', 'red');
    jQuery('#to').addClass('is-invalid').parent().addClass('text-danger');
  } else {
    status = true;
  }
  return status;
}