
jQuery(document).ready(function() {
  jQuery('.select2').select2();

  if(setPhoto !== '') {
    let canvas = document.getElementById('canvas');
    let ctx = canvas.getContext('2d');
    let image = new Image();
    image.onload = function() {
      ctx.drawImage(image, 0, 0);
    };
    image.src = setPhoto;
  } else {
    let patientImage = image;
    if(patientImage !== '') {
      patientImage = THEMEBASEURL+'uploads/user/'+image;
      const canvas = document.getElementById('canvas');
      const context = canvas.getContext('2d');
      const img = new Image();
      img.src = patientImage;
      img.onload = () => {
        if(image === 'default.png') {
          context.drawImage(img, 0, 0, 300, 150);
        } else {
          context.drawImage(img, 0, 0);
        }
      };
    }
  }

  // Code For Image Capture
  let video = document.getElementById('video');
  // Get access to the camera!
  if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // Not adding `{ audio: true }` since we only want video now
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
      video.srcObject = stream;
      video.play();
    });
  }

  let canvas  = document.getElementById('canvas');
  let context = canvas.getContext('2d');
  // Trigger photo take
  document.getElementById('snap').addEventListener('click', function() {
    context.drawImage(video, 0, 0, 300, 150);
    let img = canvas.toDataURL('image/png');
    document.getElementById('image').value = img;
  });
});

