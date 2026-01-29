<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('ambulance_slno')?></th>
                <th><?=$this->lang->line('ambulance_name')?></th>
                <th><?=$this->lang->line('ambulance_number')?></th>
                <th><?=$this->lang->line('ambulance_model')?></th>
                <?php if(permissionChecker('ambulance_view') || permissionChecker('ambulance_edit') || permissionChecker('ambulance_delete')) { ?>
                    <th><?=$this->lang->line('ambulance_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($ambulances)) { foreach($ambulances as $ambulance) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('ambulance_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('ambulance_name')?>"><?=$ambulance->name?></td>
                    <td data-title="<?=$this->lang->line('ambulance_number')?>"><?=$ambulance->number?></td>
                    <td data-title="<?=$this->lang->line('ambulance_model')?>"><?=$ambulance->model?></td>
                    <?php if(permissionChecker('ambulance_edit') || permissionChecker('ambulance_edit') || permissionChecker('ambulance_delete')) { ?>
                        <td class="center" data-title="<?=$this->lang->line('ambulance_action')?>">
                            <?=btn_modal_view('ambulance/view', $ambulance->ambulanceID, $this->lang->line('ambulance_view'))?>
                            <?=btn_edit('ambulance/edit/'.$ambulance->ambulanceID, $this->lang->line('ambulance_edit'))?>
                            <?=btn_delete('ambulance/delete/'.$ambulance->ambulanceID, $this->lang->line('ambulance_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($ambulances) && permissionChecker('ambulance_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('ambulance_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('ambulance_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>