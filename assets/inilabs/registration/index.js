
jQuery(document).ready(function() {
  'use strict';
  jQuery('.select2').select2();

  jQuery(document).on('click', '#newuhid', function() {
    jQuery.ajax({
      type: 'GET',
      url: THEMEBASEURL+'registration/newuhid',
      dataType: 'html',
      success: function(data) {
        jQuery('#username').val(data);
      }
    });
  });

  if(setPhoto !== '') {
    let canvas = document.getElementById('canvas');
    let ctx = canvas.getContext('2d');
    let image = new Image();
    image.onload = function() {
      ctx.drawImage(image, 0, 0);
    };
    image.src = setPhoto;
  }

  let video = document.getElementById('video');
  if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
      video.srcObject = stream;
      video.play();
    });
  }

  let canvas  = document.getElementById('canvas');
  let context = canvas.getContext('2d');

  document.getElementById('snap').addEventListener('click', function() {
    context.drawImage(video, 0, 0, 300, 150);
    let img = canvas.toDataURL('image/png');
    document.getElementById('image').value = img;
  });
});