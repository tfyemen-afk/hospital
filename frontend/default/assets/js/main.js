jQuery(document).ready(function () {
  'use strict';
  jQuery('.mobile-menu').slicknav({
    prependTo : '.navbar-header',
    parentTag : 'liner',
    allowParentLinks : true,
    duplicate : true,
    label : '',
    closedSymbol : '<i class="fa fa-angle-right"></i>',
    openedSymbol : '<i class="fa fa-angle-down"></i>',
  });

  jQuery('#main-slider').on('translate.owl.carousel', function () {
    jQuery('.caption h2, .caption p').removeClass('animated fadeInUp').css('opacity', '0');
    jQuery('.caption .btn-1').removeClass('animated fadeInDown').css('opacity', '0');
  });

  jQuery('#main-slider').on('translated.owl.carousel', function () {
    jQuery('.caption h2, .caption p').addClass('animated fadeInUp').css('opacity', '1');
    jQuery('.caption .btn-1').addClass('animated fadeInDown').css('opacity', '1');
  });

  jQuery('.venobox_custom').venobox({
    framewidth : '700px',
    frameheight : '500px',
    border : '2px',
    bgcolor : '#fff',
    titleattr : 'data-title',
    numeratio : true,
    infinigall : true
  });

  jQuery('.counterup').counterUp({
    delay : 10,
    time : 1000
  });


  jQuery('#scroll').fadeOut();
  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > 100) {
      jQuery('#scroll').fadeIn(500);
    } else {
      jQuery('#scroll').fadeOut(600);
    }
  });

  jQuery(document).on('click', '#scroll', function () {
    jQuery('html,body').animate({scrollTop : 0}, 800);
    return false;
  });

  jQuery('#main-slider').owlCarousel({
    items : 1,
    loop : true,
    autoplay : true,
    smartSpeed : 1200,
    dots : false,
    nav : true,
    navText : [ '<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>' ],
  });
});






