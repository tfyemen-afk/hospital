<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('itemsupplier_slno')?></th>
                <th><?=$this->lang->line('itemsupplier_company_name')?></th>
                <th><?=$this->lang->line('itemsupplier_supplier_name')?></th>
                <?php if(permissionChecker('itemsupplier_view') || permissionChecker('itemsupplier_edit') || permissionChecker('itemsupplier_delete')) { ?>
                    <th><?=$this->lang->line('itemsupplier_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($itemsuppliers)) {foreach($itemsuppliers as $itemsupplier) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('itemsupplier_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('itemsupplier_company_name')?>"><?=namesorting($itemsupplier->companyname,30)?></td>
                    <td data-title="<?=$this->lang->line('itemsupplier_supplier_name')?>"><?=namesorting($itemsupplier->suppliername,30)?></td>
                    <?php if(permissionChecker('itemsupplier_view') || permissionChecker('itemsupplier_edit') || permissionChecker('itemsupplier_delete')) { ?>
                        <td data-title="<?=$this->lang->line('itemsupplier_action')?>">
                            <?=btn_modal_view('itemsupplier/view', $itemsupplier->itemsupplierID, $this->lang->line('itemsupplier_view'))?>
                            <?=btn_edit('itemsupplier/edit/'.$itemsupplier->itemsupplierID, $this->lang->line('itemsupplier_edit'))?>
                            <?=btn_delete('itemsupplier/delete/'.$itemsupplier->itemsupplierID, $this->lang->line('itemsupplier_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($itemsuppliers) && permissionChecker('itemsupplier_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('itemsupplier_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('itemsupplier_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>