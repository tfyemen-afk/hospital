$(document).ready(function () {
  'use strict';
  $(document).on('click', '#pages-select-all', function (e) {
    e.preventDefault();
    if ($(this).hasClass('checked')) {
      $('.pages-list-checked').prop('checked', false);
      $(this).removeClass('checked');
      $(this).addClass('unchecked');
    } else {
      $('.pages-list-checked').prop('checked', true);
      $(this).removeClass('unchecked');
      $(this).addClass('checked');
    }
  });

  $(document).on('click', '#posts-select-all', function (e) {
    e.preventDefault();
    if ($(this).hasClass('checked')) {
      $('.posts-list-checked').prop('checked', false);
      $(this).removeClass('checked');
      $(this).addClass('unchecked');
    } else {
      $('.posts-list-checked').prop('checked', true);
      $(this).removeClass('unchecked');
      $(this).addClass('checked');
    }
  });

  $('ol.sortable').nestedSortable({
    forcePlaceholderSize : true,
    handle : 'div',
    items : 'li',
    placeholder : 'placeholder',
    toleranceElement : '> div',
    maxLevels : 3,
    isTree : true,
  });

  $(document).on('click', '.pages-add', function () {
    let selected = [];
    $('.pages-all-checkbox input:checked').each(function () {
      selected.push($(this).attr('id'));
    });

    if (selected.length > 0) {
      $.ajax({
        type : 'POST',
        url : THEMEBASEURL + 'frontendmenu/getpage',
        data : {'page' : selected, [ CSRFNAME ] : CSRFHASH},
        dataType : 'html',
        success : function (data) {
          $('.sortable').append(data);
          $('.pages-list-checked').prop('checked', false);
        }
      });
    }
  });

  $(document).on('click', '.posts-add', function() {
    let selected = [];
    $('.posts-all-checkbox input:checked').each(function() {
      selected.push($(this).attr('id'));
    });

    if(selected.length > 0) {
      $.ajax({
        type: 'POST',
        url: THEMEBASEURL+'frontendmenu/getpost',
        data: { 'post' : selected, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          $('.sortable').append(data);
          $('.posts-list-checked').prop( 'checked', false);
        }
      });
    }
  });

  $(document).on('click', '.links-add', function() {
    let url_link_field = $('.url-link-field').val();
    let url_link_text = $('.url-link-text').val();

    let error = 0;

    if(url_link_field === 'http://' || url_link_field === 'https://') {
      error++;
      $('.url-link-field').addClass('menu-has-error');
    } else {
      $('.url-link-field').removeClass('menu-has-error');
    }

    if(error === 0) {
      $.ajax({
        type: 'POST',
        url: THEMEBASEURL+'frontendmenu/getlink',
        data: { 'url_link_field' : url_link_field, 'url_link_text' : url_link_text, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          $('.sortable').append(data);
          $('.url-link-field').val('http://');
          $('.url-link-text').val('');
        }
      });
    }
  });

  $(document).on('keyup', '#menu-settings-link', function(e) {
    if($(this).val() !== '') {
      if($(this).val().length > 253) {
        let str = $(this).val();
        str = str.substring(0, str.length - 1);
        $(this).val(str);
        toastr['error'](frontendmenu_error_label);
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
  });

  $(document).on('keyup', '.lable-text', function() {
    let id = $(this).attr('data-title');
    if($(this).val() === '') {
      $('.'+id+' span.menu-manage-title-text').html('('+frontendmenu_no_label+')');
    } else {
      if($(this).val().length > 253) {
        let str = $(this).val();
        str = str.substring(0, str.length - 1);
        $('.'+id+' span.menu-manage-title-text').html(str);
        $(this).val(str);
        $(this).attr('value', str);

        toastr['error'](frontendmenu_error_label);
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
      } else {
        $('.'+id+' span.menu-manage-title-text').html($(this).val());
        $(this).attr('value', $(this).val());
      }
    }
  });

  $(document).on('keyup', '.url-control-field', function() {
    if($(this).val() !== '') {
      $(this).attr('value', $(this).val());
    }
  });

  $(document).on('click', '.Data-remove', function(e) {
    e.preventDefault();
    let id = $(this).attr('data-title');
    $('.'+id).slideUp('normal', function() { $('.'+id).parent().remove(); } );
  });

  $(document).on('click', '.Data-cancel', function(e) {
    e.preventDefault();
    let rand = $(this).attr('rand-info');
    let oldtitle = $(this).attr('old-title');
    $('#label-'+rand).val(oldtitle);
    $('.menu-title-'+rand+' span.menu-manage-title-text').text(oldtitle);

    let oldurltitle = $(this).attr('old-url-title');
    if (typeof oldurltitle !== typeof undefined && oldurltitle !== false) {
      $('#url-label-'+rand).val(oldurltitle);
    }

    $('.menu-title-'+rand).addClass('collapsed').attr('aria-expanded', 'false');
    $('#menu-item-collapse-'+rand).removeClass('in').addClass('collapse');
  });

  $(document).on('click', '.data-up-one', function(e) {
    e.preventDefault();
    let rand = $(this).attr('rand-info');
    let current = $('.'+rand).parent();
    let previous = current.prev('li');

    if(previous.length !== 0){
      current.insertBefore(previous);
    }
  });

  $(document).on('click', '.data-down-one', function(e) {
    e.preventDefault();
    let rand = $(this).attr('rand-info');
    let current = $('.'+rand).parent();
    let next = current.next('li');

    if(next.length !== 0){
      current.insertAfter(next);
    }
  });

  $(document).on('click', '.submit-menu', function() {
    let activeMenuID = getactivefmenuID;
    let loop = 1;
    let arr = [];
    let flagArray = [];

    $('ol.sortable > li').each(function(index, value) {
      if(jQuery.inArray($(value).children().attr('data-rand'), flagArray) === -1) {
        let link = '';
        let navValue = $(value).children().children('div').eq(1).children().children().children().children().eq(1).attr('value');
        if($(value).children().attr('data-type-id') == 3) {
          navValue = $(value).children().children('div').eq(1).children().children().children('div').eq(1).children().eq(1).attr('value');
          link = $(value).children().children('div').eq(1).children().children().children('div').eq(0).children().eq(1).attr('value');
        }

        arr.push({'fmenuID' : activeMenuID, 'menu_typeID' : $(value).children().attr('data-type-id'), 'menu_parentID' : '0', 'menu_pageID' : $(value).children().attr('data-id'), 'menu_label' : navValue, 'menu_link' : link, 'menu_orderID' : loop, 'menu_rand' : $(value).children().attr('data-rand'), 'menu_rand_parentID' : '0' });
        loop++;
        flagArray.push($(value).children().attr('data-rand'));
      }

      $('ol.sortable > li#'+$(value).attr('id')+' > ol > li').each(function(index2, value2) {
        if(jQuery.inArray($(value2).children().attr('data-rand'), flagArray) === -1) {
          let link2 = '';
          let navValue2 = $(value2).children().children('div').eq(1).children().children().children().children().eq(1).attr('value');
          if($(value2).children().attr('data-type-id') == 3) {
            navValue2 = $(value2).children().children('div').eq(1).children().children().children('div').eq(1).children().eq(1).attr('value');
            link2 = $(value2).children().children('div').eq(1).children().children().children('div').eq(0).children().eq(1).attr('value');
          }

          arr.push({'fmenuID' : activeMenuID, 'menu_typeID' : $(value2).children().attr('data-type-id'), 'menu_parentID' : $(value).children().attr('data-id'), 'menu_pageID' : $(value2).children().attr('data-id'), 'menu_label' : navValue2, 'menu_link' : link2, 'menu_orderID' : loop, 'menu_rand' : $(value2).children().attr('data-rand'), 'menu_rand_parentID' : $(value).children().attr('data-rand') });
          loop++;
          flagArray.push($(value2).children().attr('data-rand'));
        }


        $('ol.sortable > li > ol> li#'+$(value2).attr('id')+' > ol > li').each(function(index3, value3) {
          if(jQuery.inArray($(value3).children().attr('data-rand'), flagArray) === -1) {
            let link3 = '';
            let navValue3 = $(value3).children().children('div').eq(1).children().children().children().children().eq(1).attr('value');
            if($(value3).children().attr('data-type-id') == 3) {
              navValue3 = $(value3).children().children('div').eq(1).children().children().children('div').eq(1).children().eq(1).attr('value');
              link3 = $(value3).children().children('div').eq(1).children().children().children('div').eq(0).children().eq(1).attr('value');
            }

            arr.push({'fmenuID' : activeMenuID, 'menu_typeID' : $(value3).children().attr('data-type-id'), 'menu_parentID' : $(value2).children().attr('data-id'), 'menu_pageID' : $(value3).children().attr('data-id'), 'menu_label' : navValue3, 'menu_link' : link3, 'menu_orderID' : loop, 'menu_rand' : $(value3).children().attr('data-rand'), 'menu_rand_parentID' : $(value2).children().attr('data-rand') });
            loop++;
            flagArray.push($(value3).children().attr('data-rand'));
          }
        });
      });
    });

    if(arr.length > 0) {
      let locationtop = 0;
      if ($('#locations-top').is(':checked')) {
        locationtop = parseInt($('#locations-top').val());
      }

      let locationsocial = 0;
      if ($('#locations-social').is(':checked')) {
        locationsocial = parseInt($('#locations-social').val());
      }

      let editmenuID = $('#topbar-menu-select-property').val();
      let menuname = $('#menu-name').val();

      $.ajax({
        type: 'POST',
        url: THEMEBASEURL+'frontendmenu/savemenu',
        data: { 'elements' : arr, 'locationtop' : locationtop, 'locationsocial' : locationsocial, 'editmenuID' : editmenuID, 'menuname' : menuname, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          let response = JSON.parse(data);
          if (response.status === false) {
            let responseerror = response.errors;
            if(responseerror.length > 0) {
              let i;
              for (i = 0; i < responseerror.length; i++) {
                toastr['error'](responseerror[i]);
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
          } else {
            window.location.reload();
          }
        }
      });
    }
  });

  $(document).on('click', '.btn-menu-add-type', function() {
    let fmenuID = $('#topbar-menu-select-property').val();
    if(fmenuID !== '') {
      $.ajax({
        type: 'POST',
        url: THEMEBASEURL+'frontendmenu/editmenutoggle',
        data: { 'fmenuID' : fmenuID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          window.location.reload();
        }
      });
    }
  });

  $(document).on('click', '.delete-btn', function(e) {
    e.preventDefault();
    let fmenuID = getactivefmenuID;
    if(fmenuID !== '') {
      $.ajax({
        type: 'POST',
        url: THEMEBASEURL+'frontendmenu/deletefmenu',
        data: { 'fmenuID' : fmenuID, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          window.location.reload();
        }
      });
    }
  });

  $(document).on('click', '.create-new-menu', function(e) {
    e.preventDefault();
    $('#menu-manage-box').hide();
    $('#create-new-menu-box').show();
    $('.submit-btn').attr( 'disabled', 'disabled');
    $('.select-btn').remove();
    $('#menu-settings').addClass('disable-menu');
  });

  $(document).on('click', '.submit-create-new-menu', function() {
    let createmenuname = $('#create-menu-name').val();
    if(createmenuname !== '') {
      $.ajax({
        type: 'POST',
        url: THEMEBASEURL+'frontendmenu/createnewmenu',
        data: { 'menuname' : createmenuname, [CSRFNAME] : CSRFHASH },
        dataType: 'html',
        success: function(data) {
          let response = JSON.parse(data);
          if (response.status === false) {
            toastr['error'](response.error);
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
          } else {
            window.location.reload();
          }
        }
      });
    }
  });

});

























