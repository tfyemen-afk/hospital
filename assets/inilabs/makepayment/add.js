jQuery(document).ready(function () {
  'use strict';
  jQuery('.monthpicker').datepicker({
    autoclose : true,
    format : 'mm-yyyy',
    viewMode : 'months',
    minViewMode : 'months'
  });

  jQuery('#total_hours').on('keyup', function () {
    let net_salary = '<?=jQuerynetsalary?>';
    let total_hours = jQuery(this).val();
    if (parseFloat(total_hours)) {
      jQuery('#hourdis').html('(' + total_hours + ' X ' + net_salary + ')').addClass('text-danger');
      jQuery('#payment_amount').val(parseFloat(total_hours * net_salary));
    }
  });
});