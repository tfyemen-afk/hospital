jQuery(document).ready(function() {
  'use strict';
  jQuery(document).on('click', '#admin', function () {
    jQuery('input[name=username]').val('admin');
    jQuery('input[name=password]').val('123456');
  });

  jQuery(document).on('click', '#doctor', function () {
    jQuery('input[name=username]').val('doctor');
    jQuery('input[name=password]').val('123456');
  });

  jQuery(document).on('click', '#patient', function () {
    jQuery('input[name=username]').val('1001');
    jQuery('input[name=password]').val('123456');
  });

  jQuery(document).on('click', '#accountant', function () {
    jQuery('input[name=username]').val('accountant');
    jQuery('input[name=password]').val('123456');
  });

  jQuery(document).on('click', '#biller', function () {
    jQuery('input[name=username]').val('biller');
    jQuery('input[name=password]').val('123456');
  });

  jQuery(document).on('click', '#pharmacist', function () {
    jQuery('input[name=username]').val('pharmacist');
    jQuery('input[name=password]').val('123456');
  });

  jQuery(document).on('click', '#pathologist', function () {
    jQuery('input[name=username]').val('pathologist');
    jQuery('input[name=password]').val('123456');
  });

  jQuery(document).on('click', '#radiologist', function () {
    jQuery('input[name=username]').val('radiologist');
    jQuery('input[name=password]').val('123456');
  });

  jQuery(document).on('click',  '#receptionist', function () {
    jQuery('input[name=username]').val('receptionist');
    jQuery('input[name=password]').val('123456');
  });
});