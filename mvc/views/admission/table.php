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
                    $displayDoctorArray['0'] = '— '.$this->lang->line('admission_please_select').' —';
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
                <th><?=$this->lang->line('admission_slno')?></th>
                <th><?=$this->lang->line('admission_uhid')?></th>
                <th><?=$this->lang->line('admission_name')?></th>
                <th><?=$this->lang->line('admission_doctor')?></th>
                <th><?=$this->lang->line('admission_date')?></th>
                <?php if(permissionChecker('admission_view') || permissionChecker('admission_edit') || permissionChecker('admission_delete')) { ?>
                    <th><?=$this->lang->line('admission_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($admissions)) { foreach($admissions as $admission) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('admission_slno')?>"><?=$i;?></td>
                    <td data-title="<?=$this->lang->line('admission_uhid')?>"><?=$admission->patientID;?></td>
                    <td data-title="<?=$this->lang->line('admission_name')?>"><?=$admission->name?></td>
                    <td data-title="<?=$this->lang->line('admission_doctor')?>"><?=isset($doctors[$admission->doctorID]) ? $doctors[$admission->doctorID]->name : ''?></td>
                    <td data-title="<?=$this->lang->line('admission_date')?>"><?=date('d M Y h:i A', strtotime($admission->admissiondate)) ; ?></td>
                    <?php if(permissionChecker('admission_view') || permissionChecker('admission_edit') || permissionChecker('admission_delete')) { ?>
                        <td data-title="<?=$this->lang->line('admission_action')?>">
                            <?=btn_view('admission/view/'.$admission->admissionID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('admission_view'))?>
                            <?php if($admission->status && $admission->prescriptionstatus) { ?>
                                <?=btn_custom('admission_view', 'admission/prescription/'.$admission->admissionID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('admission_prescription'))?>
                            <?php } elseif($admission->status == 0) { ?>
                                <?=btn_edit('admission/edit/'.$admission->admissionID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('admission_edit'))?>
                                <?=btn_delete('admission/delete/'.$admission->admissionID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('admission_delete'))?>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>