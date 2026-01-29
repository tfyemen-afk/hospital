<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('medicinemanufacturer_slno')?></th>
                <th><?=$this->lang->line('medicinemanufacturer_name')?></th>
                <th><?=$this->lang->line('medicinemanufacturer_supplier_name')?></th>
                <th><?=$this->lang->line('medicinemanufacturer_phone')?></th>
                <?php if(permissionChecker('medicinemanufacturer_view') || permissionChecker('medicinemanufacturer_edit') || permissionChecker('medicinemanufacturer_delete')) { ?>
                    <th><?=$this->lang->line('medicinemanufacturer_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($medicinemanufacturers)) { foreach($medicinemanufacturers as $medicinemanufacturer) { $i++; ?>
                <tr class="odd gradeX">
                    <td data-title="<?=$this->lang->line('medicinemanufacturer_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('medicinemanufacturer_name')?>"><?=$medicinemanufacturer->name?></td>
                    <td data-title="<?=$this->lang->line('medicinemanufacturer_supplier_name')?>"> <?=$medicinemanufacturer->supplier_name?></td>
                    <td data-title="<?=$this->lang->line('medicinemanufacturer_phone')?>"> <?=$medicinemanufacturer->phone?></td>
                    <?php if(permissionChecker('medicinemanufacturer_view') || permissionChecker('medicinemanufacturer_edit') || permissionChecker('medicinemanufacturer_delete')) { ?>
                        <td data-title="<?=$this->lang->line('medicinemanufacturer_action')?>" class="center">
                            <?=btn_modal_view('medicinemanufacturer/view', $medicinemanufacturer->medicinemanufacturerID, $this->lang->line('medicinemanufacturer_view'))?>
                            <?=btn_edit('medicinemanufacturer/edit/'.$medicinemanufacturer->medicinemanufacturerID, $this->lang->line('medicinemanufacturer_edit'))?>
                            <?=btn_delete('medicinemanufacturer/delete/'.$medicinemanufacturer->medicinemanufacturerID, $this->lang->line('medicinemanufacturer_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($medicinemanufacturers)) { if(permissionChecker('medicinemanufacturer_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('medicinemanufacturer_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('medicinemanufacturer_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } } ?>
