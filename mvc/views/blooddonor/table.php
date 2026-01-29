<?php if($this->data['loginroleID'] != 3) { ?>
<div class="btn-group pull-right">
    <a href="<?=site_url('blooddonor/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('blooddonor_today')?></a>
    <a href="<?=site_url('blooddonor/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('blooddonor_month')?></a>
    <a href="<?=site_url('blooddonor/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('blooddonor_year')?></a>
    <a href="<?=site_url('blooddonor/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('blooddonor_all')?></a>
</div>
<br>
<br>
<?php } ?>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('blooddonor_slno')?></th>
                <th><?=$this->lang->line('blooddonor_name')?></th>
                <th><?=$this->lang->line('blooddonor_gender')?></th>
                <th><?=$this->lang->line('blooddonor_age')?></th>
                <th><?=$this->lang->line('blooddonor_bloodgroupID')?></th>
                <th><?=$this->lang->line('blooddonor_phone')?></th>
                <?php if(permissionChecker('blooddonor_view') || permissionChecker('blooddonor_edit') || permissionChecker('blooddonor_delete')) { ?>
                    <th><?=$this->lang->line('blooddonor_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($blooddonors)) { foreach($blooddonors as $blooddonor) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('blooddonor_slno')?>"><?=$i;?></td>
                    <td data-title="<?=$this->lang->line('blooddonor_name')?>"><?=ucfirst($blooddonor->name); ?></td>
                    <td data-title="<?=$this->lang->line('blooddonor_gender')?>"><?=ucfirst(($blooddonor->gender == '1') ? $this->lang->line('blooddonor_male') : $this->lang->line('blooddonor_female')); ?></td>
                    <td data-title="<?=$this->lang->line('blooddonor_age')?>"><?=$blooddonor->age ;?></td>
                    <td data-title="<?=$this->lang->line('blooddonor_bloodgroupID')?>"><?=isset($bloodgroups[$blooddonor->bloodgroupID]) ? $bloodgroups[$blooddonor->bloodgroupID]->bloodgroup : '';?></td>
                    <td data-title="<?=$this->lang->line('blooddonor_phone')?>"><?=$blooddonor->phone ;?></td>
                    <?php if(permissionChecker('blooddonor_view') || permissionChecker('blooddonor_edit') || permissionChecker('blooddonor_edit') || permissionChecker('blooddonor_delete')) { ?>
                        <td data-title="<?=$this->lang->line('blooddonor_action')?>">
                            <?=btn_modal_view('blooddonor/view/', $blooddonor->blooddonorID, $this->lang->line('blooddonor_view'))?>
                            <?=btn_edit('blooddonor/edit/'.$blooddonor->blooddonorID.'/'.$displayID, $this->lang->line('blooddonor_edit'))?>
                            <?=btn_delete('blooddonor/delete/'.$blooddonor->blooddonorID.'/'.$displayID, $this->lang->line('blooddonor_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($blooddonors)) { if(permissionChecker('blooddonor_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('blooddonor_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('blooddonor_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } } ?>
