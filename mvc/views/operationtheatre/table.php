<?php if($loginroleID != 3) { ?>
<div class="btn-group pull-right">
    <a href="<?=site_url('operationtheatre/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('operationtheatre_today')?></a>
    <a href="<?=site_url('operationtheatre/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('operationtheatre_month')?></a>
    <a href="<?=site_url('operationtheatre/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('operationtheatre_year')?></a>
    <a href="<?=site_url('operationtheatre/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('operationtheatre_all')?></a>
</div>
<br>
<br>
<?php } ?>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('operationtheatre_slno')?></th>
                <th><?=$this->lang->line('operationtheatre_operation_name')?></th>
                <th><?=$this->lang->line('operationtheatre_operation_type')?></th>
                <th><?=$this->lang->line('operationtheatre_patient_name')?></th>
                <?php if(permissionChecker('operationtheatre_view') || permissionChecker('operationtheatre_edit') || permissionChecker('operationtheatre_delete')) { ?>
                    <th><?=$this->lang->line('operationtheatre_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($operationtheatres)) { foreach($operationtheatres as $operationtheatre) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('operationtheatre_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('operationtheatre_operation_name')?>"><?=$operationtheatre->operation_name?></td>
                    <td data-title="<?=$this->lang->line('operationtheatre_operation_type')?>"><?=$operationtheatre->operation_type?></td>
                    <td data-title="<?=$this->lang->line('operationtheatre_patient_name')?>">
                        <?=$operationtheatre->name .' - '.$operationtheatre->patientID?>
                    </td>
                    <?php if(permissionChecker('operationtheatre_view') || permissionChecker('operationtheatre_edit') || permissionChecker('operationtheatre_delete')) { ?>
                        <td class="center" data-title="<?=$this->lang->line('operationtheatre_action')?>">
                            <?=btn_view('operationtheatre/view/'.$operationtheatre->operationtheatreID.'/'.$displayID, $this->lang->line('operationtheatre_view'))?>
                            <?=btn_edit('operationtheatre/edit/'.$operationtheatre->operationtheatreID.'/'.$displayID, $this->lang->line('operationtheatre_edit'))?>
                            <?=btn_delete('operationtheatre/delete/'.$operationtheatre->operationtheatreID.'/'.$displayID, $this->lang->line('operationtheatre_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>