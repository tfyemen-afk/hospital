<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('medicine_slno')?></th>
                <th><?=$this->lang->line('medicine_name')?></th>
                <th><?=$this->lang->line('medicine_category')?></th>
                <th><?=$this->lang->line('medicine_manufacturer')?></th>
                <?php if(permissionChecker('medicine_view') || permissionChecker('medicine_edit') || permissionChecker('medicine_delete')) { ?>
                    <th><?=$this->lang->line('medicine_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($medicines)) {foreach($medicines as $medicine) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('medicine_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicine_name')?>">
                        <?=$medicine->name;?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicine_category')?>">
                        <?=isset($medicinecategorys[$medicine->medicinecategoryID]) ? $medicinecategorys[$medicine->medicinecategoryID] : "&nbsp"?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicine_manufacturer')?>">
                        <?=isset($medicinemanufacturers[$medicine->medicinemanufacturerID]) ? $medicinemanufacturers[$medicine->medicinemanufacturerID] : "&nbsp"?>
                    </td>
                    <?php if(permissionChecker('medicine_edit') || permissionChecker('medicine_delete')) { ?>
                        <td data-title="<?=$this->lang->line('medicine_action')?>">
                            <?=btn_modal_view('medicine/view', $medicine->medicineID, $this->lang->line('medicine_view'))?>
                            <?=btn_edit('medicine/edit/'.$medicine->medicineID, $this->lang->line('medicine_edit'))?>
                            <?=btn_delete('medicine/delete/'.$medicine->medicineID, $this->lang->line('medicine_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($medicines)) { if(permissionChecker('medicine_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('medicine_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('medicine_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } } ?>