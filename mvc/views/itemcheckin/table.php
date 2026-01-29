<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('itemcheckin_slno')?></th>
                <th><?=$this->lang->line('itemcheckin_item')?></th>
                <th><?=$this->lang->line('itemcheckin_supplier')?></th>
                <th><?=$this->lang->line('itemcheckin_date')?></th>
                <th><?=$this->lang->line('itemcheckin_quantity')?></th>
                <th><?=$this->lang->line('itemcheckin_status')?></th>
                <?php if(permissionChecker('itemcheckin_view') || permissionChecker('itemcheckin_edit') || permissionChecker('itemcheckin_delete')) { ?>
                    <th><?=$this->lang->line('itemcheckin_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($itemcheckins)) {foreach($itemcheckins as $itemcheckin) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('itemcheckin_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('itemcheckin_item')?>"><?=isset($items[$itemcheckin->itemID]) ? $items[$itemcheckin->itemID]->name : ''?></td>
                    <td data-title="<?=$this->lang->line('itemcheckin_supplier')?>"><?=isset($itemsuppliers[$itemcheckin->supplierID]) ? $itemsuppliers[$itemcheckin->supplierID]->companyname : ''?></td>
                    <td data-title="<?=$this->lang->line('itemcheckin_date')?>"><?=app_datetime($itemcheckin->date)?></td>
                    <td data-title="<?=$this->lang->line('itemcheckin_quantity')?>"><?=$itemcheckin->quantity?></td>
                    <td data-title="<?=$this->lang->line('itemcheckin_status')?>"><?=($itemcheckin->status) ? $this->lang->line('itemcheckin_not_available') : $this->lang->line('itemcheckin_available')?></td>
                    <?php if(permissionChecker('itemcheckin_view') || permissionChecker('itemcheckin_edit') || permissionChecker('itemcheckin_delete')) { ?>
                        <td data-title="<?=$this->lang->line('itemcheckin_action')?>">
                            <?=btn_modal_view('itemcheckin/view', $itemcheckin->itemcheckinID, $this->lang->line('itemcheckin_view'))?>
                            <?=btn_edit('itemcheckin/edit/'.$itemcheckin->itemcheckinID, $this->lang->line('itemcheckin_edit'))?>
                            <?=btn_delete('itemcheckin/delete/'.$itemcheckin->itemcheckinID, $this->lang->line('itemcheckin_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($itemcheckins) && permissionChecker('itemcheckin_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('itemcheckin_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('itemcheckin_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>