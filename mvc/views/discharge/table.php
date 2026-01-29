<div class="btn-group pull-right">
    <a href="<?=site_url('discharge/'.$displayViewType.'/'.($displayViewType == 'edit' ? $discharge->dischargeID.'/1' : 1))?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('discharge_today')?></a>
    <a href="<?=site_url('discharge/'.$displayViewType.'/'.($displayViewType == 'edit' ? $discharge->dischargeID.'/2' : 2))?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('discharge_month')?></a>
    <a href="<?=site_url('discharge/'.$displayViewType.'/'.($displayViewType == 'edit' ? $discharge->dischargeID.'/3' : 3))?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('discharge_year')?></a>
    <a href="<?=site_url('discharge/'.$displayViewType.'/'.($displayViewType == 'edit' ? $discharge->dischargeID.'/4' : 4))?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('discharge_all')?></a>
</div>
<br>
<br>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
        <tr>
            <th><?=$this->lang->line('discharge_slno')?></th>
            <th><?=$this->lang->line('discharge_uhid')?></th>
            <th><?=$this->lang->line('discharge_name')?></th>
            <th><?=$this->lang->line('discharge_gender')?></th>
            <th><?=$this->lang->line('discharge_date')?></th>
            <?php if(permissionChecker('discharge_view') || permissionChecker('discharge_edit') || permissionChecker('discharge_delete')) { ?>
                <th><?=$this->lang->line('discharge_action')?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php if(inicompute($discharges)) { $i = 1; foreach ($discharges as $discharge) { ?>
            <tr>
                <td data-title="<?=$this->lang->line('discharge_slno')?>"><?=$i?></td>
                <td data-title="<?=$this->lang->line('discharge_uhid')?>"><?=$discharge->patientID?></td>
                <td data-title="<?=$this->lang->line('discharge_name')?>"><?=$discharge->name?></td>
                <td data-title="<?=$this->lang->line('discharge_gender')?>"><?=(isset($genders[$discharge->gender]) ? $genders[$discharge->gender] : '')?></td>
                <td data-title="<?=$this->lang->line('discharge_date')?>"><?=app_datetime($discharge->date)?></td>
                <?php if(permissionChecker('discharge_view') || permissionChecker('discharge_edit') || permissionChecker('discharge_delete')) { ?>
                    <td data-title="<?=$this->lang->line('discharge_action')?>">
                        <?=btn_view('discharge/view/'.$discharge->dischargeID.'/'.$displayID, $this->lang->line('discharge_view'))?>
                        <?=btn_edit('discharge/edit/'.$discharge->dischargeID.'/'.$displayID, $this->lang->line('discharge_edit'))?>
                        <?=btn_delete('discharge/delete/'.$discharge->dischargeID.'/'.$displayID, $this->lang->line('discharge_delete'))?>
                    </td>
                <?php } ?>
            </tr>
            <?php $i++; }} ?>

        </tbody>
    </table>
</div>