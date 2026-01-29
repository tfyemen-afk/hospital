<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('itemstore_slno')?></th>
                <th><?=$this->lang->line('itemstore_name')?></th>
                <th><?=$this->lang->line('itemstore_code')?></th>
                <th><?=$this->lang->line('itemstore_in_charge')?></th>
                <?php if(permissionChecker('itemstore_view') || permissionChecker('itemstore_edit') || permissionChecker('itemstore_delete')) { ?>
                    <th><?=$this->lang->line('itemstore_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($itemstores)) {foreach($itemstores as $itemstore) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('itemstore_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('itemstore_name')?>"><?=namesorting($itemstore->name,30)?></td>
                    <td data-title="<?=$this->lang->line('itemstore_code')?>"><?=namesorting($itemstore->code,30)?></td>
                    <td data-title="<?=$this->lang->line('itemstore_in_charge')?>"><?=namesorting($itemstore->incharge,30)?></td>
                    <?php if(permissionChecker('itemstore_view') || permissionChecker('itemstore_edit') || permissionChecker('itemstore_delete')) { ?>
                        <td data-title="<?=$this->lang->line('itemstore_action')?>">
                            <?=btn_modal_view('itemstore/view', $itemstore->itemstoreID, $this->lang->line('itemstore_view'))?>
                            <?=btn_edit('itemstore/edit/'.$itemstore->itemstoreID, $this->lang->line('itemstore_edit'))?>
                            <?=btn_delete('itemstore/delete/'.$itemstore->itemstoreID, $this->lang->line('itemstore_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($itemstores) && permissionChecker('itemstore_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('itemstore_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('itemstore_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>