<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('testlabel_slno')?></th>
                <th><?=$this->lang->line('testlabel_name')?></th>
                <th><?=$this->lang->line('testlabel_test_category')?></th>
                <?php if(permissionChecker('testlabel_edit') || permissionChecker('testlabel_delete')) { ?>
                    <th><?=$this->lang->line('testlabel_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($testlabels)) {foreach($testlabels as $testlabel) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('testlabel_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('testlabel_name')?>"><?=$testlabel->name?></td>
                    <td data-title="<?=$this->lang->line('testlabel_test_category')?>"><?=isset($testcategorys[$testlabel->testcategoryID]) ? $testcategorys[$testlabel->testcategoryID] : ''?></td>
                    <?php if(permissionChecker('testlabel_edit') || permissionChecker('testlabel_delete')) { ?>
                        <td data-title="<?=$this->lang->line('testlabel_action')?>">
                            <?=btn_edit('testlabel/edit/'.$testlabel->testlabelID, $this->lang->line('testlabel_edit'))?>
                            <?=btn_delete('testlabel/delete/'.$testlabel->testlabelID, $this->lang->line('testlabel_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>