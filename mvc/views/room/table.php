<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('room_slno')?></th>
                <th><?=$this->lang->line('room_name')?></th>
                <th><?=$this->lang->line('room_description')?></th>
                <?php if(permissionChecker('room_view') ||permissionChecker('room_edit') || permissionChecker('room_delete') || permissionChecker('room_view')) { ?>
                    <th><?=$this->lang->line('room_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($rooms)) {foreach($rooms as $room) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('room_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('room_name')?>"><?=$room->name?></td>
                    <td data-title="<?=$this->lang->line('room_description')?>"><?=namesorting($room->description, 40)?></td>
                    <?php if(permissionChecker('room_view') || permissionChecker('room_delete') || permissionChecker('room_view')) { ?>
                        <td data-title="<?=$this->lang->line('room_action')?>">
                            <?=btn_modal_view('room/view', $room->roomID, $this->lang->line('room_view'))?>
                            <?=btn_edit('room/edit/'.$room->roomID, $this->lang->line('room_edit'))?>
                            <?=btn_delete('room/delete/'.$room->roomID, $this->lang->line('room_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($rooms) && permissionChecker('room_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('room_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('room_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>