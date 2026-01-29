
jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();
  jQuery('#date').datetimepicker({
    autoclose : true,
    format : 'dd-mm-yyyy HH:ii P',
    showMeridian : 'day',
    todayHighlight : true,
  });
});