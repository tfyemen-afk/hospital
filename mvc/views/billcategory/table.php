<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('billcategory_slno')?></th>
                <th><?=$this->lang->line('billcategory_name')?></th>
                <th><?=$this->lang->line('billcategory_description')?></th>
                <?php if(permissionChecker('billcategory_edit') || permissionChecker('billcategory_delete')) { ?>
                    <th><?=$this->lang->line('billcategory_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($billcategorys)) {foreach($billcategorys as $billcategory) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('billcategory_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('billcategory_name')?>"><?=$billcategory->name?></td>
                    <td data-title="<?=$this->lang->line('billcategory_description')?>"><?=namesorting($billcategory->description, 45)?></td>
                    <?php if(permissionChecker('billcategory_edit') || permissionChecker('billcategory_delete')) { ?>
                        <td data-title="<?=$this->lang->line('billcategory_action')?>">
                            <?=btn_edit('billcategory/edit/'.$billcategory->billcategoryID, $this->lang->line('billcategory_edit'))?>
                            <?=btn_delete('billcategory/delete/'.$billcategory->billcategoryID, $this->lang->line('billcategory_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>