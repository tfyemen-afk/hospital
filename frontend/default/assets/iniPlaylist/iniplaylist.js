function audioPlaylist(ele, playBox, track, playerID, playlistID) {
  'use strict';
  jQuery('#' + playerID).attr('src', track);
  document.getElementById(playerID).play();
  jQuery('#' + playlistID).find('li.active').removeClass('active');
  jQuery(ele).parent().addClass('active');
  let trackName = jQuery(ele).find('div.item__title').text();
  jQuery('#' + playBox).find('span.npTitle').text(trackName);
}

function videoPlaylist(ele, playBox, track, playerID, playlistID) {
  'use strict';
  jQuery('#' + playerID).attr('src', track);
  document.getElementById(playerID).play();
  jQuery('#' + playlistID).find('li.active').removeClass('active');
  jQuery(ele).parent().addClass('active');
}
