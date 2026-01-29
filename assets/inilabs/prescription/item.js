jQuery(document).ready(function () {
  'use strict';

  jQuery(document).on('click', '#addMedicine', function () {
    let error = 0;
    let medicine = jQuery('#medicine').val();
    let instruction = jQuery('#instruction').val();

    if (medicine === '') {
      error++;
      jQuery('#medicine-div').addClass('text-danger');
      jQuery('#medicine').addClass('is-invalid');
      jQuery('#medicine-error').text('The medicine field is required');
    } else {
      if (medicine.length > 500) {
        error++;
        jQuery('#medicine-div').addClass('text-danger');
        jQuery('#medicine').addClass('is-invalid');
        jQuery('#medicine-error').text('The medicine field cannot exceed 500 characters in length.');
      } else {
        jQuery('#medicine-div').removeClass('text-danger');
        jQuery('#medicine').removeClass('is-invalid');
        jQuery('#medicine-error').text('');
      }
    }

    if (instruction === '') {
      error++;
      jQuery('#instruction-div').addClass('text-danger');
      jQuery('#instruction').addClass('is-invalid');
      jQuery('#instruction-error').text('The instruction field is required');
    } else {
      if (instruction.length > 500) {
        error++;
        jQuery('#instruction-div').addClass('text-danger');
        jQuery('#instruction').addClass('is-invalid');
        jQuery('#instruction-error').text('The instruction field cannot exceed 500 characters in length.');
      } else {
        jQuery('#instruction-div').removeClass('text-danger');
        jQuery('#instruction').removeClass('is-invalid');
        jQuery('#instruction-error').text('');
      }

    }

    if (error === 0) {
      let appendData = medicineItemDesign(medicine, instruction);
      jQuery('#medicineList').append(appendData);
    }
  });

  function medicineItemDesign(medicine, instruction, trCount = 0) {
    if (trCount === 0) {
      trCount = jQuery('#medicineList').children().length;
      trCount++;
    }

    let counter = parseInt(jQuery('#counter').val());
    counter++;
    jQuery('#counter').val(counter);

    let text = '<tr id="tr_' + trCount + '" trID="' + trCount + '">';
    text += '<td>';
    text += trCount;
    text += '</td>';

    text += '<td id="medicine_' + trCount + '">';
    text += medicine;
    text += '<input name="medicine_' + trCount + '" style="display:none" type="text" value="' + medicine + '">';
    text += '</td>';

    text += '<td id="instruction_' + trCount + '">';
    text += instruction;
    text += '<input name="instruction_' + trCount + '" style="display:none" type="text" value="' + instruction + '">';
    text += '</td>';

    text += '<td>';
    text += '<button type="button" class="btn btn-danger btn-sm btn-medicine-delete" id="' + trCount + '" style="padding:1px 3px;font-size:14px">';
    text += '<i class="fa fa-trash-o"></i>';
    text += '</button>';
    text += '</td>';
    text += '</tr>';

    return text;
  }

  jQuery(document).on('click', '.btn-medicine-delete', function () {
    let id = parseInt(jQuery(this).attr('id'));
    let medicineArray = [];
    let instructionArray = [];
    let trCount = jQuery('#medicineList').children().length;

    for (let j = 1; j <= trCount; j++) {
      medicineArray[ j ] = jQuery('#medicine_' + j).text();
      instructionArray[ j ] = jQuery('#instruction_' + j).text();
    }

    jQuery('#medicineList').children().remove();
    jQuery('#counter').val(0);
    let k = 1;
    for (let i = 1; i <= trCount; i++) {
      if (id !== i) {
        jQuery('#medicineList').append(medicineItemDesign(medicineArray[ i ], instructionArray[ i ], k));
        k++;
      }
    }
  });
});