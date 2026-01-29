<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('itemcategory_slno')?></th>
                <th><?=$this->lang->line('itemcategory_name')?></th>
                <th><?=$this->lang->line('itemcategory_description')?></th>
                <?php if(permissionChecker('itemcategory_view') || permissionChecker('itemcategory_edit') || permissionChecker('itemcategory_delete')) { ?>
                    <th><?=$this->lang->line('itemcategory_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($itemcategorys)) {foreach($itemcategorys as $itemcategory) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('itemcategory_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('itemcategory_name')?>"><?=namesorting($itemcategory->name,30)?></td>
                    <td data-title="<?=$this->lang->line('itemcategory_description')?>"><?=namesorting($itemcategory->description,30)?></td>
                    <?php if(permissionChecker('itemcategory_view') || permissionChecker('itemcategory_edit') || permissionChecker('itemcategory_delete')) { ?>
                        <td data-title="<?=$this->lang->line('itemcategory_action')?>">
                            <?=btn_modal_view('itemcategory/view', $itemcategory->itemcategoryID, $this->lang->line('itemcategory_view'))?>
                            <?=btn_edit('itemcategory/edit/'.$itemcategory->itemcategoryID, $this->lang->line('itemcategory_edit'))?>
                            <?=btn_delete('itemcategory/delete/'.$itemcategory->itemcategoryID, $this->lang->line('itemcategory_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($itemcategorys) && permissionChecker('itemcategory_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('itemcategory_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('itemcategory_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>