<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('ward_slno')?></th>
                <th><?=$this->lang->line('ward_name')?></th>
                <th><?=$this->lang->line('ward_floor')?></th>
                <th><?=$this->lang->line('ward_room')?></th>
                <?php if(permissionChecker('ward_edit') || permissionChecker('ward_delete')) { ?>
                    <th><?=$this->lang->line('ward_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($wards)) {foreach($wards as $ward) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('ward_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('ward_name')?>">
                        <?=$ward->name;?>
                    </td>
                    <td data-title="<?=$this->lang->line('ward_floor')?>">
                        <?=isset($floors[$ward->floorID]) ? $floors[$ward->floorID] : "&nbsp";?>
                    </td>
                    <td data-title="<?=$this->lang->line('ward_room')?>">
                        <?=isset($rooms[$ward->roomID]) ? $rooms[$ward->roomID] : "&nbsp";?>
                    </td>
                    <?php if(permissionChecker('ward_view') || permissionChecker('ward_edit') || permissionChecker('ward_delete')) { ?>
                        <td data-title="<?=$this->lang->line('ward_action')?>">
                            <?=btn_modal_view('ward/view', $ward->wardID, $this->lang->line('ward_view'))?>
                            <?=btn_edit('ward/edit/'.$ward->wardID, $this->lang->line('ward_edit'))?>
                            <?=btn_delete('ward/delete/'.$ward->wardID, $this->lang->line('ward_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($wards)) { if(permissionChecker('ward_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('ward_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('ward_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } } ?>