<?php if($loginroleID != 3) { ?>
<div class="btn-group pull-right">
    <a href="<?=site_url('prescription/index/1/'.$displaytypeID.'/'.$displayuhID)?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('prescription_today')?></a>
    <a href="<?=site_url('prescription/index/2/'.$displaytypeID.'/'.$displayuhID)?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('prescription_month')?></a>
    <a href="<?=site_url('prescription/index/3/'.$displaytypeID.'/'.$displayuhID)?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('prescription_year')?></a>
    <a href="<?=site_url('prescription/index/4/'.$displaytypeID.'/'.$displayuhID)?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('prescription_all')?></a>
</div>
<br>
<br>
<?php } ?>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('prescription_slno')?></th>
                <th><?=$this->lang->line('prescription_uhid')?></th>
                <th><?=$this->lang->line('prescription_name')?></th>
                <th><?=$this->lang->line('prescription_type')?></th>
                <th><?=$this->lang->line('prescription_create_date')?></th>
                <th><?=$this->lang->line('prescription_gender')?></th>
                <th><?=$this->lang->line('prescription_age')?></th>
                <?php if(permissionChecker('prescription_edit') || permissionChecker('prescription_delete') || permissionChecker('prescription_view')) { ?>
                    <th><?=$this->lang->line('prescription_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($prescriptions)) { foreach($prescriptions as $prescription) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('prescription_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('prescription_uhid')?>"><?=$prescription->patientID?></td>
                    <td data-title="<?=$this->lang->line('prescription_name')?>"><?=$prescription->name?></td>
                    <td data-title="<?=$this->lang->line('prescription_type')?>"><?=($prescription->patienttypeID == 0) ? $this->lang->line('prescription_opd') : $this->lang->line('prescription_ipd')?></td>
                    <td data-title="<?=$this->lang->line('prescription_create_date')?>"><?=app_datetime($prescription->create_date)?></td>
                    <td data-title="<?=$this->lang->line('prescription_gender')?>"><?=isset($genders[$prescription->gender]) ? $genders[$prescription->gender] : ''?></td>
                    <td data-title="<?=$this->lang->line('prescription_age')?>"><?=stringtoage($prescription->age_day, $prescription->age_month, $prescription->age_year)?></td>
                    <?php if(permissionChecker('prescription_edit') || permissionChecker('prescription_delete') || permissionChecker('prescription_view')) { ?>
                        <td data-title="<?=$this->lang->line('prescription_action')?>">
                            <?=btn_view('prescription/view/'.$prescription->prescriptionID.'/'.$displayID.'/'.$displaytypeID.'/'.$displayuhID, $this->lang->line('prescription_view'))?>
                            <?=btn_edit('prescription/edit/'.$prescription->prescriptionID.'/'.$displayID.'/'.$displaytypeID.'/'.$displayuhID, $this->lang->line('prescription_edit'))?>
                            <?=btn_delete('prescription/delete/'.$prescription->prescriptionID.'/'.$displayID.'/'.$displaytypeID.'/'.$displayuhID, $this->lang->line('prescription_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>