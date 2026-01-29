<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('item_slno')?></th>
                <th><?=$this->lang->line('item_name')?></th>
                <th><?=$this->lang->line('item_category')?></th>
                <th><?=$this->lang->line('item_description')?></th>
                <?php if(permissionChecker('item_view') || permissionChecker('item_edit') || permissionChecker('item_delete')) { ?>
                    <th><?=$this->lang->line('item_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($items)) {foreach($items as $item) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('item_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('item_name')?>"><?=namesorting($item->name,30)?></td>
                    <td data-title="<?=$this->lang->line('item_category')?>"><?=isset($itemcategory[$item->categoryID]) ? $itemcategory[$item->categoryID]->name : ''?></td>
                    <td data-title="<?=$this->lang->line('item_description')?>"><?=namesorting($item->description,30)?></td>
                    <?php if(permissionChecker('item_view') || permissionChecker('item_edit') || permissionChecker('item_delete')) { ?>
                        <td data-title="<?=$this->lang->line('item_action')?>">
                            <?=btn_modal_view('item/view', $item->itemID, $this->lang->line('item_view'))?>
                            <?=btn_edit('item/edit/'.$item->itemID, $this->lang->line('item_edit'))?>
                            <?=btn_delete('item/delete/'.$item->itemID, $this->lang->line('item_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($items) && permissionChecker('item_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('item_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('item_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>