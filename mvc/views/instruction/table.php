<?php if($this->data['loginroleID'] != 3) { ?>
    <div class="btn-group pull-right">
        <a href="<?=site_url('instruction/'.$displayViewType.'/'.($displayViewType == 'edit' ? $instruction->instructionID.'/1/'.$displayuhID : '1/'.$displayuhID))?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('instruction_today')?></a>
        <a href="<?=site_url('instruction/'.$displayViewType.'/'.($displayViewType == 'edit' ? $instruction->instructionID.'/2/'.$displayuhID : '2/'.$displayuhID))?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('instruction_month')?></a>
        <a href="<?=site_url('instruction/'.$displayViewType.'/'.($displayViewType == 'edit' ? $instruction->instructionID.'/3/'.$displayuhID : '3/'.$displayuhID))?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('instruction_year')?></a>
        <a href="<?=site_url('instruction/'.$displayViewType.'/'.($displayViewType == 'edit' ? $instruction->instructionID.'/4/'.$displayuhID : '4/'.$displayuhID))?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('instruction_all')?></a>
    </div>
    <br>
    <br>
<?php } ?>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('instruction_slno')?></th>
                <th><?=$this->lang->line('instruction_uhid')?></th>
                <th><?=$this->lang->line('instruction_name')?></th>
                <th><?=$this->lang->line('instruction_admission_date')?></th>
                <th><?=$this->lang->line('instruction_gender')?></th>
                <th><?=$this->lang->line('instruction_age')?></th>
                <?php if(permissionChecker('instruction_view')) { ?>
                    <th><?=$this->lang->line('instruction_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($patients)) { foreach($patients as $patient) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('instruction_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('instruction_uhid')?>"><?=$patient->uhid?></td>
                    <td data-title="<?=$this->lang->line('instruction_name')?>"><?=$patient->name?></td>
                    <td data-title="<?=$this->lang->line('instruction_admission_date')?>"><?=app_datetime($patient->admissiondate)?></td>
                    <td data-title="<?=$this->lang->line('instruction_gender')?>"><?=isset($genders[$patient->gender]) ? $genders[$patient->gender] : ''?></td>
                    <td data-title="<?=$this->lang->line('instruction_age')?>"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?></td>
                    <?php if(permissionChecker('instruction_view')) { ?>
                        <td data-title="<?=$this->lang->line('instruction_action')?>">
                            <?=btn_view('instruction/view/'.$patient->admissionID.'/'.$patient->uhid.'/'.$displayID.'/'.$displayuhID,  $this->lang->line('instruction_view'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>