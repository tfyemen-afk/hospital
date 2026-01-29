<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('medicinecategory_slno')?></th>
                <th><?=$this->lang->line('medicinecategory_name')?></th>
                <?php if(permissionChecker('medicinecategory_edit') || permissionChecker('medicinecategory_delete')) { ?>
                    <th><?=$this->lang->line('medicinecategory_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($medicinecategorys)) { foreach($medicinecategorys as $medicinecategory) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('medicinecategory_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('medicinecategory_name')?>"><?=namesorting($medicinecategory->name,30)?></td>
                    <?php if(permissionChecker('medicinecategory_edit') || permissionChecker('medicinecategory_delete')) { ?>
                        <td data-title="<?=$this->lang->line('medicinecategory_action')?>">
                            <?=btn_edit('medicinecategory/edit/'.$medicinecategory->medicinecategoryID, $this->lang->line('medicinecategory_edit'))?>
                            <?=btn_delete('medicinecategory/delete/'.$medicinecategory->medicinecategoryID, $this->lang->line('medicinecategory_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>