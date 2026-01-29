<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('leavecategory_slno')?></th>
                <th><?=$this->lang->line('leavecategory_category')?></th>
                <?php if(permissionChecker('leavecategory_edit') || permissionChecker('leavecategory_delete')) { ?>
                    <th><?=$this->lang->line('leavecategory_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($leavecategorys)) { foreach($leavecategorys as $leavecategory) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('leavecategory_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('leavecategory_category')?>"><?=$leavecategory->leavecategory; ?></td>
                    <?php if(permissionChecker('leavecategory_edit') || permissionChecker('leavecategory_delete')) { ?>
                        <td data-title="<?=$this->lang->line('leavecategory_action')?>">
                            <?=btn_edit('leavecategory/edit/'.$leavecategory->leavecategoryID, $this->lang->line('leavecategory_edit'))?>
                            <?=btn_delete('leavecategory/delete/'.$leavecategory->leavecategoryID, $this->lang->line('leavecategory_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>