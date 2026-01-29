<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('leaveassign_slno')?></th>
                <th><?=$this->lang->line('leaveassign_role')?></th>
                <th><?=$this->lang->line('leaveassign_category')?></th>
                <th><?=$this->lang->line('leaveassign_year')?></th>
                <th><?=$this->lang->line('leaveassign_no_of_day')?></th>
                <?php if(permissionChecker('leaveassign_edit') || permissionChecker('leaveassign_delete')) { ?>
                    <th><?=$this->lang->line('leaveassign_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($leaveassigns)) { foreach($leaveassigns as $leaveassign) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('leaveassign_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('leaveassign_role')?>"><?=isset($roles[$leaveassign->roleID]) ? $roles[$leaveassign->roleID]->role : ''?></td>
                    <td data-title="<?=$this->lang->line('leaveassign_category')?>"><?=isset($leavecategorys[$leaveassign->leavecategoryID]) ? $leavecategorys[$leaveassign->leavecategoryID]->leavecategory : ''?></td>
                    <td data-title="<?=$this->lang->line('leaveassign_year')?>">
                        <?=$leaveassign->year?>
                    </td>
                    <td data-title="<?=$this->lang->line('leaveassign_no_of_day')?>">
                        <?=$leaveassign->leaveassignday?>
                    </td>
                    <?php if(permissionChecker('leaveassign_edit') || permissionChecker('leaveassign_delete')) { ?>
                        <td data-title="<?=$this->lang->line('leaveassign_action')?>">
                            <?=btn_edit('leaveassign/edit/'.$leaveassign->leaveassignID, $this->lang->line('leaveassign_edit'))?>
                            <?=btn_delete('leaveassign/delete/'.$leaveassign->leaveassignID, $this->lang->line('leaveassign_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>