jQuery(document).on('click', '.viewModalBtn', function () {
  var roomID = jQuery(this).attr('id');
  if (roomID > 0) {
    jQuery.ajax({
      type : 'POST',
      url : THEMEBASEURL+'room/view',
      data : {'roomID' : roomID, [ CSRFNAME ] : CSRFHASH },
      dataType : 'html',
      success : function (data) {
        jQuery('#viewModal .modal-body').html(data);
      }
    });
  }
});