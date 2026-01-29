$(document).ready(function () {
  'use strict';

  $(document).on('blur', '#file_title, #file_artist, #file_album, #file_caption, #file_alt_text, #file_description', function () {
    let file_title = $('#file_title').val();
    let file_artist = $('#file_artist').val();
    let file_album = $('#file_album').val();
    let file_caption = $('#file_caption').val();
    let file_alt_text = $('#file_alt_text').val();
    let file_description = $('#file_description').val();
    let hidden_id_field = $('#hidden-id-field').val();

    let setFileInfoBind = {
      'hidden_id_field' : hidden_id_field,
      'file_title' : file_title,
      'file_artist' : file_artist,
      'file_album' : file_album,
      'file_caption' : file_caption,
      'file_alt_text' : file_alt_text,
      'file_description' : file_description,
      [ CSRFNAME ] : CSRFHASH
    };

    $.ajax({
      type : 'POST',
      url : THEMEBASEURL + 'post/setFileInfo',
      data : setFileInfoBind,
      dataType : 'html'
    });
  });

  function stringPermalink(title) {
    title = title.trim();
    title = title.toLowerCase();
    title = title.replace(/[^a-zA-Z0-9]/g, ' ');

    let newString = '';
    let f = 0;
    for (let i = 0, len = title.length; i < len; i++) {
      if (title[ i ] != ' ') {
        newString += title[ i ];
        f = 0;
      } else if (title[ i ] == ' ' && f == 0) {
        newString += '-';
        f = 1;
      }
    }
    return newString;
  }

  $(document).on('blur', '#title', function () {
    let title = $(this).val();
    if (title != '') {
      let url = $('#url').val();
      if (url == '') {
        title = stringPermalink(title);

        $('.permalink-area').show();
        $('.editable-permalink-name').text(title);
        $('#url').val(title);
      }
    }
  });

  $(document).on('click', '#permalink-edit', function () {
    $('#editable-permalink-section').show();
    $('#permalink-edit').hide();
    $('.editable-permalink-name').hide();

    let editablePermalinkName = $('.editable-permalink-name').text();
    editablePermalinkName = stringPermalink(editablePermalinkName);
    $('#url').val(editablePermalinkName).show();
    $('.permalink').removeClass('permalink').addClass('permalink-edit');
  });

  $(document).on('click', '#save-permalink', function () {
    let url = $('#url').val();
    url = stringPermalink(url);
    $('.editable-permalink-name').text(url).show();
    $('#url').val(url).show();
    $('#url').hide();
    $('#editable-permalink-section').hide();
    $('#permalink-edit').show();
    $('.permalink-edit').removeClass('permalink-edit').addClass('permalink');
  });

  $(document).on('click', '#cancel-permalink', function () {
    $('.editable-permalink-name').show();
    $('#url').hide();
    $('#editable-permalink-section').hide();
    $('#permalink-edit').show();
    $('.permalink-edit').removeClass('permalink-edit').addClass('permalink');
  });

  $(document).on('click', '#category-add', function () {
    $('#category-edit-show').toggle();
  });

  $('.post-status-select').hide();
  $(document).on('click', '.post-edit', function (e) {
    e.preventDefault();
    let id = $(this).attr('id');
    $('#' + id + '-show').show();
    $('#' + id).hide();
  });

  $(document).on('click', '.save-post-status', function (e) {
    e.preventDefault();
    let id = $(this).attr('id');

    if (id == 'save-status') {
      let saveStatusValue = $('#status').val();

      let btnSaveStatus = '';
      let btnInSaveStatus = '';
      if (saveStatusValue == 'draft') {
        saveStatusValue = post_draft;
        btnSaveStatus = "draft";
        btnInSaveStatus = post_save_draft;
      } else if (saveStatusValue == 'review') {
        saveStatusValue = post_pending_review;
        btnSaveStatus = "review";
        btnInSaveStatus = post_save_as_pending;
      }

      $('.btn-save-status').val(btnSaveStatus);
      $('.btn-save-status').html(btnInSaveStatus);
      $('#status-message').text(saveStatusValue);
      $('#draft-edit-show').hide();
      $('#draft-edit').show();
    } else if (id == 'save-visibility') {
      let saveVisibilityValue = $('input[name=visibility]:checked').val();
      if (saveVisibilityValue == 1) {
        saveVisibilityValue = post_public;
      } else if (saveVisibilityValue == 2) {
        saveVisibilityValue = post_password_protected;
      } else if (saveVisibilityValue == 3) {
        saveVisibilityValue = post_private;
      }
      $('#visibility-message').text(saveVisibilityValue);
      $('#visibility-edit-show').hide();
      $('#visibility-edit').show();
    } else if (id == 'save-publish') {
      let publish_year = $('#publish_year').val();
      let publish_month = $('#publish_month').val();
      let publish_day = $('#publish_day').val();
      let publish_hour = $('#publish_hour').val();
      let publish_minute = $('#publish_minute').val();

      let get_date = publish_year + Math.abs(publish_month) + Math.abs(publish_day) + Math.abs(publish_hour) + Math.abs(publish_minute);

      get_date = parseInt(get_date);
      let currentdate = new Date();
      let current_date = currentdate.getFullYear() + '' + (currentdate.getMonth() + 1) + currentdate.getDate() + currentdate.getHours() + currentdate.getMinutes();
      current_date = parseInt(current_date);
      let get_date_convator = publish_day + '/' + publish_month + '/' + publish_year + ' ' + publish_hour + ':' + publish_minute;

      if (validDateChecker(get_date_convator)) {
        if (current_date !== get_date) {
          let monthNames = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
          if (typeof monthNames[ Math.abs(parseInt(publish_month) - 1) ] !== 'undefined' && monthNames[ Math.abs(parseInt(publish_month) - 1) ] !== null) {
            $('#publish_message').text(monthNames[ Math.abs(parseInt(publish_month) - 1) ] + ' ' + publish_day + ', ' + publish_year + ' @ ' + publish_hour + ':' + publish_minute);
          } else {
            $('#publish_message').text(post_immediately);
          }
        } else {
          $('#publish_message').text(post_immediately);
        }
        $('#publish_year').removeClass('date-error-color');
        $('#publish_month').removeClass('date-error-color');
        $('#publish_day').removeClass('date-error-color');
        $('#publish_hour').removeClass('date-error-color');
        $('#publish_minute').removeClass('date-error-color');
        $('#publish-edit-show').hide();
        $('#publish-edit').show();
      } else {
        $('#publish_message').text('');
        $('#publish_year').addClass('date-error-color');
        $('#publish_month').addClass('date-error-color');
        $('#publish_day').addClass('date-error-color');
        $('#publish_hour').addClass('date-error-color');
        $('#publish_minute').addClass('date-error-color');
      }
    } else if (id == 'save-category') {
      let category = $('#categoryitem').val();
      $('#categoryitem').val('');
      if (category != '' && category != null) {
        $.ajax({
          dataType : "json",
          type : 'POST',
          url : THEMEBASEURL + 'post/addCategory',
          data : {'category' : category, [ CSRFNAME ] : CSRFHASH},
          dataType : "html",
          success : function (data) {
            let response = jQuery.parseJSON(data);
            if (response.status) {
              $('.all-category-list').append(response.content);
            }
          }
        });
      }
    }
  });

  function validDateChecker(dt) {
    try {
      /*"dd/MM/yyyy HH:mm"*/
      let isValidDate = false;
      let arr1 = dt.split('/');
      let year = 0;
      let month = 0;
      let day = 0;
      let hour = 0;
      let minute = 0;
      let sec = 0;
      if (arr1.length == 3) {
        let arr2 = arr1[ 2 ].split(' ');
        if (arr2.length == 2) {
          let arr3 = arr2[ 1 ].split(':');
          try {
            year = parseInt(arr2[ 0 ], 10);
            month = parseInt(arr1[ 1 ], 10);
            day = parseInt(arr1[ 0 ], 10);
            hour = parseInt(arr3[ 0 ], 10);
            minute = parseInt(arr3[ 1 ], 10);
            //sec = parseInt(arr3[0],10);
            sec = 0;
            let isValidTime = false;
            if (hour >= 0 && hour <= 23 && minute >= 0 && minute <= 59 && sec >= 0 && sec <= 59)
              isValidTime = true;
            else if (hour == 24 && minute == 0 && sec == 0)
              isValidTime = true;

            if (isValidTime) {
              let isLeapYear = false;
              if (year % 4 == 0)
                isLeapYear = true;

              if ((month == 4 || month == 6 || month == 9 || month == 11) && (day >= 0 && day <= 30))
                isValidDate = true;
              else if ((month != 2) && (day >= 0 && day <= 31))
                isValidDate = true;

              if (!isValidDate) {
                if (isLeapYear) {
                  if (month == 2 && (day >= 0 && day <= 29))
                    isValidDate = true;
                }
                else {
                  if (month == 2 && (day >= 0 && day <= 28))
                    isValidDate = true;
                }
              }
            }
          }
          catch (er) {
            isValidDate = false;
          }
        }
      }
      return isValidDate;
    }
    catch (err) {
      return isValidDate;
    }
  }

  $(document).on('change', '#visibility-all-data-set input', function () {
    let getValue = $('input[name=visibility]:checked', '#visibility-all-data-set').val();
    if (getValue == 2) {
      $('#protected_password').parent().show();
    } else {
      $('#protected_password').parent().hide();
    }
  });

  $(document).on('click', '.cancel-post-status', function (e) {
    e.preventDefault();
    let id = $(this).attr('id');
    if (id == 'cancle-draft') {
      $('#draft-edit-show').hide();
      $('#draft-edit').show();
    } else if (id == 'cancel-visibility') {
      $('#visibility-edit-show').hide();
      $('#visibility-edit').show();
    } else if (id == 'cancel-publish') {
      $('#publish-edit-show').hide();
      $('#publish-edit').show();
    }
  });

  $(document).on('click', '.flipup', function () {
    let id = $(this).attr('id');
    if (id == 'publish') {
      if ($('#' + id).children().attr('class') == 'fa fa-caret-up') {
        $('#publish-box').hide();
        $('#publish-box-footer').hide();
        $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
      } else {
        $('#publish-box').show();
        $('#publish-box-footer').show();
        $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
      }
    } else if (id == 'attribute') {
      if ($('#' + id).children().attr('class') == 'fa fa-caret-up') {
        $('#attribute-box').hide();
        $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
      } else {
        $('#attribute-box').show();
        $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
      }
    } else if (id == 'featuredimage') {
      if ($('#' + id).children().attr('class') == 'fa fa-caret-up') {
        $('#featuredimage-box').hide();
        $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
      } else {
        $('#featuredimage-box').show();
        $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
      }
    } else if (id == 'sliderimages') {
      if ($('#' + id).children().attr('class') == 'fa fa-caret-up') {
        $('#sliderimages-box').hide();
        $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
      } else {
        $('#sliderimages-box').show();
        $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
      }
    } else if (id == 'category') {
      if ($('#' + id).children().attr('class') == 'fa fa-caret-up') {
        $('#category-box').hide();
        $('#' + id).children().removeClass('fa-caret-up').addClass('fa-angle-down');
      } else {
        $('#category-box').show();
        $('#' + id).children().removeClass('fa-angle-down').addClass('fa-caret-up');
      }
    }
  });

  $(document).on('click', '#set-featured-img', function (e) {
    e.preventDefault();
  });

  $(document).on('click', '.slider-delete', function () {
    let id = $(this).attr('id');
    let getHiddenData = $('#hidden_slider_images').val();
    let setHiddenData = getHiddenData.replace("," + id, '');
    $('#hidden_slider_images').val(setHiddenData);
    $(this).parent().remove();
  });
});


function removeFeatureImage(ele, hiddenfield, imageSetClass) {
  'use strict';
  $('.feauret-img-show').children().remove();
  $('#featured_image').val('');
  $('.feauret-img-show').removeAttr('data-toggle data-target');
  $('#set-featured-img').removeClass('hide');
  $('#remove-set-featured-img').addClass('hide');
}

function audioPlaylist(ele, playBox, track, playerID, playlistID) {
  'use strict';
  $('#' + playerID).attr('src', track);
  document.getElementById(playerID).play();
  $('#' + playlistID).find('li.active').removeClass('active');
  $(ele).parent().addClass('active');
  let trackName = $(ele).find('div.item__title').text();
  $('#' + playBox).find('span.npTitle').text(trackName);
}

function videoPlaylist(ele, playBox, track, playerID, playlistID) {
  'use strict';
  $('#' + playerID).attr('src', track);
  document.getElementById(playerID).play();
  $('#' + playlistID).find('li.active').removeClass('active');
  $(ele).parent().addClass('active');
}

function fileUpload(ele) {
  'use strict';
  let getID = $(ele).parent().parent().parent().parent().attr('id');
  let formData = new FormData($('#' + getID)[ 0 ]);
  formData.append([ CSRFNAME ], CSRFHASH);
  $.ajax({
    dataType : 'json',
    type : 'POST',
    url : THEMEBASEURL + 'post/fileUpload',
    data : formData,
    async : true,
    dataType : 'html',
    success : function (data) {
      let response = jQuery.parseJSON(data);
      if (response.status == true) {
        $('#insertMedia').children().remove();
        $('#createGallery').children().remove();
        $('#createAudioPlaylist').children().remove();
        $('#createVideoPlaylist').children().remove();
        $('#featuredImage').children().remove();
        $('#setfeaturedImage').children().remove();
        $('#setSliderImages').children().remove();

        $('#insertMedia').append(response.insert_media_content);
        $('#createGallery').append(response.create_gallery_content);
        $('#createVideoPlaylist').append(response.create_video_playlist_content);
        $('#createAudioPlaylist').append(response.create_audio_playlist_content);
        $('#featuredImage').append(response.featured_image_content);
        $('#setfeaturedImage').append(response.set_featured_image_content);
        $('#setSliderImages').append(response.set_slider_images_content);
      } else {
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
    },
    cache : false,
    contentType : false,
    processData : false
  });
}

function setFileToEditor(ele, hiddenfield, ulClass) {
  'use strict';
  var hiddenfield = $('#' + hiddenfield).val();
  let id = $(ele).attr('id');

  if ((hiddenfield != '' && hiddenfield != null) && (ulClass != '' && ulClass != null)) {
    let setAllDataBind = {
      'allID' : hiddenfield,
      'ulclass_type' : ulClass,
      'media_type' : 1,
      'send_status' : 'defined',
      'file_title' : $('#file_title').val(),
      'file_artist' : $('#file_artist').val(),
      'file_album' : $('#file_album').val(),
      'file_caption' : $('#file_caption').val(),
      'file_alt_text' : $('#file_alt_text').val(),
      'file_description' : $('#file_description').val(),
      [ CSRFNAME ] : CSRFHASH,
    };

    $.ajax({
      dataType : 'json',
      type : 'POST',
      url : THEMEBASEURL + 'post/setFileToEditor',
      data : setAllDataBind,
      dataType : 'html',
      success : function (data) {
        let response = jQuery.parseJSON(data);
        if (response.status) {
          $('#insertMedia').children().remove();
          $('#createGallery').children().remove();
          $('#createAudioPlaylist').children().remove();
          $('#createVideoPlaylist').children().remove();
          $('#featuredImage').children().remove();
          $('#setfeaturedImage').children().remove();
          $('#setSliderImages').children().remove();

          $('#insertMedia').append(response.insert_media_content);
          $('#createGallery').append(response.create_gallery_content);
          $('#createVideoPlaylist').append(response.create_video_playlist_content);
          $('#createAudioPlaylist').append(response.create_audio_playlist_content);
          $('#featuredImage').append(response.featured_image_content);
          $('#setfeaturedImage').append(response.set_featured_image_content);
          $('#setSliderImages').append(response.set_slider_images_content);
        }

        if (id == 'insert_into_page') {
          if (response.status) {
            let oldData = $('.note-editable').html();
            $('#write-content').summernote('code', oldData + response.content);
          }
        } else if (id == 'create_a_new_gallery') {
          if (response.status) {
            let oldData = $('.note-editable').html();
            $('#write-content').summernote('code', oldData + response.content);
          }
        } else if (id == 'create_a_new_playlist') {
          if (response.status) {
            let oldData = $('.note-editable').html();
            $('#write-content').summernote('code', oldData + response.content);
          }
        } else if (id == 'create_a_new_video_playlist') {
          if (response.status) {
            let oldData = $('.note-editable').html();
            $('#write-content').summernote('code', oldData + response.content);
          }
        } else if (id == 'set_featured_image') {
          if (response.status) {
            let img = response.content;
            // let rep = img.replace('height', 'xxx');
            $('#featured_image').val(hiddenfield);
            $('.feauret-img-show').html(img).attr('data-toggle', 'modal').attr('data-target', '#SetFeaturedImage');
            $('#set-featured-img').addClass('hide');
            $('#remove-set-featured-img').removeClass('hide');
          }
        } else if (id == 'set_slider_images') {
          $('#hidden_slider_images').val(hiddenfield);
          $('.slider-pluck').children().remove();
          $('.slider-pluck').html(response.content);
        }
      }
    });
  } else {
    toastr[ 'error' ]('Please select one.');
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

function getFileInfo(ele, activeType, filetype, status, hiddenfield, combind, combindname) {
  'use strict';
  var id = $(ele).attr('id');
  if ($(ele).hasClass("selected") == false) {
    if (combind == true) {
      $('.' + combindname + '_image').find('button:first').remove();
      $('.' + combindname + '_image').removeClass('selected');

      $('.' + combindname + '_audio').find('button:first').remove();
      $('.' + combindname + '_audio').removeClass('selected');

      $('.' + combindname + '_video').find('button:first').remove();
      $('.' + combindname + '_video').removeClass('selected');
    }

    if (filetype == 'image') {
      var media_type = 1;
      var selectData = '<button type="button" class="check"><span class="media-modal-icon"></span><span class="sr-only">Deselect</span></button>';
      var childrenData = $(ele).children().html();
      if (status == 'single') {
        $('.' + $(ele).attr('class')).find('button:first').remove();
        $('.' + $(ele).attr('class')).removeClass('selected');
        $(ele).addClass('selected');
        $(ele).children().html(childrenData + selectData);

        $('#' + hiddenfield).val('');
        $('#' + hiddenfield).val(id);
      } else if (status == 'multi') {
        $(ele).addClass('selected');
        $(ele).children().html(childrenData + selectData);
        var getHiddenData = $('#' + hiddenfield).val();
        $('#' + hiddenfield).val(getHiddenData + ',' + id);
      }
    } else if (filetype == 'audio' || filetype == 'video') {
      if (filetype == 'audio') {
        var icon = 'fa fa-file-audio-o';
        var media_type = 2;
      } else {
        icon = 'fa fa-file-video-o';
        var media_type = 3;
      }
      var icon_name = '<i class="' + icon + '"></i>';
      var selectData = '<button type="button" class="check"><span class="media-modal-icon"></span><span class="sr-only">Deselect</span></button>';
      var childrenData = $(ele).find('div:last').html();
      var video_name = '<div class="video-title">' + $(ele).find('div:last').html() + '</div>';
      if (status == 'single') {
        $('.' + $(ele).attr('class')).find('button:first').remove();
        $('.' + $(ele).attr('class')).removeClass('selected');
        $(ele).addClass('selected');
        $(ele).html('<div class="thumb">' + icon_name + selectData + '</div>' + video_name);

        $('#' + hiddenfield).val('');
        $('#' + hiddenfield).val(id);
      } else if (status == 'multi') {
        $(ele).addClass('selected');
        $(ele).html('<div class="thumb">' + icon_name + selectData + '</div>' + video_name);
        var getHiddenData = $('#' + hiddenfield).val();
        $('#' + hiddenfield).val(getHiddenData + ',' + id);
      }
    }

    if (typeof $('#file_url').val() == 'undefined') {
      var setAllDataBind = {"id" : id, "media_type" : media_type, 'send_status' : 'undefined', [ CSRFNAME ] : CSRFHASH};
    } else {
      var setAllDataBind = {
        'id' : id,
        'media_type' : media_type,
        'send_status' : 'defined',
        'file_title' : $('#file_title').val(),
        'file_artist' : $('#file_artist').val(),
        'file_album' : $('#file_album').val(),
        'file_caption' : $('#file_caption').val(),
        'file_alt_text' : $('#file_alt_text').val(),
        'file_description' : $('#file_description').val(),
        [ CSRFNAME ] : CSRFHASH,
      };
    }

    $.ajax({
      dataType : "json",
      type : 'POST',
      url : THEMEBASEURL + 'post/getFileInfo',
      data : setAllDataBind,
      dataType : "html",
      success : function (data) {
        var response = jQuery.parseJSON(data);
        if (response.file_status == true) {
          $('#' + activeType).children().remove();
          $('#' + activeType).html(response.content);
        }
      },
    });
  } else {
    $('#' + activeType).children().remove();
    if (filetype == 'image') {
      if (status == 'single') {
        $(ele).find('button:first').remove();
        $(ele).removeClass('selected');
        $('#' + hiddenfield).val('');
      } else if (status == 'multi') {
        $(ele).find('button:first').remove();
        $(ele).removeClass('selected');

        var getHiddenData = $('#' + hiddenfield).val();
        var setHiddenData = getHiddenData.replace("," + id, '');
        $('#' + hiddenfield).val(setHiddenData);
      }
    } else if (filetype == 'audio' || filetype == 'video') {
      if (status == 'single') {
        $(ele).find('button:first').remove();
        $(ele).removeClass('selected');
        $('#' + hiddenfield).val('');
      } else if (status == 'multi') {
        $(ele).find('button:first').remove();
        $(ele).removeClass('selected');

        var getHiddenData = $('#' + hiddenfield).val();
        var setHiddenData = getHiddenData.replace("," + id, '');
        $('#' + hiddenfield).val(setHiddenData);
      }
    }
  }
}

function deleteFileInfo(ele) {
  'use strict';
  var id = $(ele).attr('id');

  if (id) {
    $.ajax({
      dataType : "json",
      type : "POST",
      url : THEMEBASEURL + 'post/deleteFileInfo',
      data : {"id" : id, [ CSRFNAME ] : CSRFHASH},
      dataType : "html",
      success : function (data) {
        var response = jQuery.parseJSON(data);
        if (response.status == true) {
          $('#insertMedia').children().remove();
          $('#createGallery').children().remove();
          $('#createAudioPlaylist').children().remove();
          $('#createVideoPlaylist').children().remove();
          $('#featuredImage').children().remove();
          $('#setfeaturedImage').children().remove();
          $('#setSliderImages').children().remove();

          $('#insertMedia').append(response.insert_media_content);
          $('#createGallery').append(response.create_gallery_content);
          $('#createAudioPlaylist').append(response.create_audio_playlist_content);
          $('#createVideoPlaylist').append(response.create_video_playlist_content);
          $('#featuredImage').append(response.featured_image_content);
          $('#setfeaturedImage').append(response.set_featured_image_content);
          $('#setSliderImages').append(response.set_slider_images_content);
        } else {
          toastr[ "error" ](response.message);
          toastr.options = {
            "closeButton" : true,
            "debug" : false,
            "newestOnTop" : false,
            "progressBar" : false,
            "positionClass" : "toast-top-right",
            "preventDuplicates" : false,
            "onclick" : null,
            "showDuration" : "500",
            "hideDuration" : "500",
            "timeOut" : "5000",
            "extendedTimeOut" : "1000",
            "showEasing" : "swing",
            "hideEasing" : "linear",
            "showMethod" : "fadeIn",
            "hideMethod" : "fadeOut"
          }
        }
      }
    });
  }
}
