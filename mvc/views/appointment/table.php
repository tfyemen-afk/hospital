<?php
$time = (function($time) {
    if($time > 60) {
        $hours  = (int)($time/60);
        $minute = ($time%60);
        return lzero($hours) . ':' .lzero($minute) .' M';
    }
    return lzero($time) .' M';
});


$replace = (function($url) {
    return str_replace('http:', 'https:', $url);
});
?>

<?php if($loginroleID != 3) { ?>
    <div class="pull-right">
        <div class="form-row">
            <?php if($loginroleID != 3) { ?>
                <div class="col">
                    <input type="text" class="form-control" id="displayDate" name="displayDate" value="<?=set_value('displayDate', $displayDate)?>">
                </div>
            <?php } ?>
            <?php if($loginroleID != 2 && $loginroleID != 3) { ?>
                <div class="col">
                    <?php
                    $displayDoctorArray['0'] = '— '.$this->lang->line('appointment_please_select').' —';
                    if(inicompute($doctors)) {
                        foreach ($doctors as $doctor) {
                            $displayDoctorArray[$doctor->userID] = $doctor->name;
                        }
                    }
                    $errorClass = form_error('displayDoctorID') ? 'is-invalid' : '';
                    echo form_dropdown('displayDoctorID', $displayDoctorArray,  set_value('displayDoctorID', $displayDoctorID), 'id="displayDoctorID" class="form-control select2"');
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <br>
    <br>
<?php } ?>

<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
        <tr>
            <th><?=$this->lang->line('appointment_slno')?></th>
            <th><?=$this->lang->line('appointment_uhid')?></th>
            <th><?=$this->lang->line('appointment_name')?></th>
            <th><?=$this->lang->line('appointment_doctor')?></th>
            <th><?=$this->lang->line('appointment_date')?></th>
            <?php if(permissionChecker('appointment_view') || permissionChecker('appointment_edit') || permissionChecker('appointment_delete')) { ?>
                <th><?=$this->lang->line('appointment_action')?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php $i = 0; if(inicompute($appointments)) { foreach($appointments as $appointment) { $i++; ?>
            <tr>
                <td data-title="<?=$this->lang->line('appointment_slno')?>"><?=$i;?></td>
                <td data-title="<?=$this->lang->line('appointment_uhid')?>"><?=$appointment->patientID;?></td>
                <td data-title="<?=$this->lang->line('appointment_name')?>"><?=$appointment->name?></td>
                <td data-title="<?=$this->lang->line('appointment_doctor')?>"><?=isset($doctors[$appointment->doctorID]) ? $doctors[$appointment->doctorID]->name : ''?></td>
                <td data-title="<?=$this->lang->line('appointment_date')?>"><?=app_datetime($appointment->appointmentdate)?></td>
                <?php if(permissionChecker('appointment_view') || permissionChecker('appointment_edit') || permissionChecker('appointment_delete')) { ?>
                    <td data-title="<?=$this->lang->line('appointment_action')?>">
                        <?=btn_view('appointment/view/'.$appointment->appointmentID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('appointment_view'))?>
                        <?php if($appointment->type==2 && !$appointment->status && $appointment->paymentstatus==1) { ?>
                            <?=  $replace(btn_sm_global('appointment/zoomview/'.$appointment->appointmentID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('join'), 'fa fa-video-camera'))?>
                        <?php } ?>
                        <?php if($appointment->status) { ?>
                            <?=btn_custom('appointment_view', 'appointment/prescription/'.$appointment->appointmentID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('appointment_prescription'))?>
                        <?php } else { ?>
                            <?=btn_edit('appointment/edit/'.$appointment->appointmentID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('appointment_edit'))?>
                            <?=btn_delete('appointment/delete/'.$appointment->appointmentID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('appointment_delete'))?>
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } } ?>
        </tbody>
    </table>
</div>

