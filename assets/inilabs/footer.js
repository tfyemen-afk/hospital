jQuery(document).ready(function () {
  'use strict';
  jQuery('.example').DataTable();
  jQuery('.example2').DataTable({'bPaginate' : false});
  jQuery(function () {
    jQuery('[data-toggle="tooltip"]').tooltip();
  });

  jQuery('ul.sidebar-nav li').each(function () {
    if (jQuery(this).attr('class') === 'active') {
      jQuery(this).parents('li').addClass('open');
      jQuery(this).parents('ul').addClass('in');
    }
  });
});




