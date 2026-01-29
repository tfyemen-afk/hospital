<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('patient_slno')?></th>
                <th><?=$this->lang->line('patient_photo')?></th>
                <th><?=$this->lang->line('patient_name')?></th>
                <th><?=$this->lang->line('patient_uhid')?></th>
                <th><?=$this->lang->line('patient_type')?></th>
                <th><?=$this->lang->line('patient_gender')?></th>
                <th><?=$this->lang->line('patient_phone')?></th>
                <?php if(permissionChecker('patient_edit') || permissionChecker('patient_delete') || permissionChecker('patient_view')) { ?>
                    <th><?=$this->lang->line('patient_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if(inicompute($patients)) { $i = 1; foreach ($patients as $patient) { ?>
                <tr>
                    <td data-title="<?=$this->lang->line('patient_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('patient_photo')?>"><img class="img-responsive table-image-size" src="<?=imagelink($patient->photo, '/uploads/user/') ?>"/></td>
                    <td data-title="<?=$this->lang->line('patient_name')?>"><?=$patient->name?></td>
                    <td data-title="<?=$this->lang->line('patient_uhid')?>"><?=$patient->patientID?></td>
                    <td data-title="<?=$this->lang->line('patient_type')?>"><?=(($patient->patienttypeID == 0) ? $this->lang->line('patient_opd') : $this->lang->line('patient_ipd'))?></td>
                    <td data-title="<?=$this->lang->line('patient_gender')?>"><?=(($patient->gender == 1) ? $this->lang->line('patient_male') : $this->lang->line('patient_female'))?></td>
                    <td data-title="<?=$this->lang->line('patient_phone')?>"><?=$patient->phone?></td>
                    <?php if(permissionChecker('patient_edit') || permissionChecker('patient_delete') || permissionChecker('patient_view')) { ?>
                        <td data-title="<?=$this->lang->line('patient_action')?>">
                            <?=btn_view('patient/view/'.$patient->patientID, $this->lang->line('patient_view'))?>
                            <?=btn_edit('patient/edit/'.$patient->patientID, $this->lang->line('patient_edit'))?>
                            <?=btn_delete('patient/delete/'.$patient->patientID, $this->lang->line('patient_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php $i++; }} ?>
            
        </tbody>
    </table>
</div>