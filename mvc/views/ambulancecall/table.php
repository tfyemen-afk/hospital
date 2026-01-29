<div class="btn-group pull-right">
    <a href="<?=site_url('ambulancecall/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('ambulancecall_today')?></a>
    <a href="<?=site_url('ambulancecall/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('ambulancecall_month')?></a>
    <a href="<?=site_url('ambulancecall/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('ambulancecall_year')?></a>
    <a href="<?=site_url('ambulancecall/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('ambulancecall_all')?></a>
</div>
<br>
<br>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('ambulancecall_slno')?></th>
                <th><?=$this->lang->line('ambulancecall_ambulance')?></th>
                <th><?=$this->lang->line('ambulancecall_date')?></th>
                <th><?=$this->lang->line('ambulancecall_patient_name')?></th>
                <?php if(permissionChecker('ambulancecall_view') || permissionChecker('ambulancecall_edit') || permissionChecker('ambulancecall_delete')) { ?>
                    <th><?=$this->lang->line('ambulancecall_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($ambulancecalls)) { foreach($ambulancecalls as $ambulancecall) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('ambulancecall_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('ambulancecall_ambulance')?>"><?=isset($ambulances[$ambulancecall->ambulanceID]) ? $ambulances[$ambulancecall->ambulanceID]->name : ''?></td>
                    <td data-title="<?=$this->lang->line('ambulancecall_date')?>"><?=app_datetime($ambulancecall->date)?></td>
                    <td data-title="<?=$this->lang->line('ambulancecall_patient_name')?>"><?=$ambulancecall->patientname?></td>
                    <?php if(permissionChecker('ambulancecall_edit') || permissionChecker('ambulancecall_edit') || permissionChecker('ambulancecall_delete')) { ?>
                        <td class="center" data-title="<?=$this->lang->line('ambulancecall_action')?>">
                            <?=btn_modal_view('ambulancecall/view', $ambulancecall->ambulancecallID, $this->lang->line('ambulancecall_view'))?>
                            <?=btn_edit('ambulancecall/edit/'.$ambulancecall->ambulancecallID.'/'.$displayID, $this->lang->line('ambulancecall_edit'))?>
                            <?=btn_delete('ambulancecall/delete/'.$ambulancecall->ambulancecallID.'/'.$displayID, $this->lang->line('ambulancecall_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($ambulancecalls) && permissionChecker('ambulancecall_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('ambulancecall_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('ambulancecall_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>