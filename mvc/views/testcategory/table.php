<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('testcategory_slno')?></th>
                <th><?=$this->lang->line('testcategory_name')?></th>
                <th><?=$this->lang->line('testcategory_description')?></th>
                <?php if(permissionChecker('testcategory_edit') || permissionChecker('testcategory_delete')) { ?>
                    <th><?=$this->lang->line('testcategory_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($testcategorys)) {foreach($testcategorys as $testcategory) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('testcategory_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('testcategory_name')?>"><?=$testcategory->name?></td>
                    <td data-title="<?=$this->lang->line('testcategory_description')?>"><?=namesorting($testcategory->description, 45)?></td>
                    <?php if(permissionChecker('testcategory_edit') || permissionChecker('testcategory_delete')) { ?>
                        <td data-title="<?=$this->lang->line('testcategory_action')?>">
                            <?=btn_edit('testcategory/edit/'.$testcategory->testcategoryID, $this->lang->line('testcategory_edit'))?>
                            <?=btn_delete('testcategory/delete/'.$testcategory->testcategoryID, $this->lang->line('testcategory_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>