<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('leaveapply_slno')?></th>
                <th><?=$this->lang->line('leaveapply_application_to')?></th>
                <th><?=$this->lang->line('leaveapply_category')?></th>
                <th><?=$this->lang->line('leaveapply_no_of_day')?></th>
                <?php if(permissionChecker('leaveapply_edit') || permissionChecker('leaveapply_delete') || permissionChecker('leaveapply_view')) { ?>
                    <th><?=$this->lang->line('leaveapply_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($leaveapplys)) { foreach($leaveapplys as $leaveapply) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('leaveapply_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('leaveapply_application_to')?>"><?=isset($userName[$leaveapply->applicationto_userID]) ? $userName[$leaveapply->applicationto_userID] : ''?></td>
                    <td data-title="<?=$this->lang->line('leaveapply_category')?>"><?=isset($leavecategorys[$leaveapply->leavecategoryID]) ? $leavecategorys[$leaveapply->leavecategoryID]->leavecategory : ''?></td>
                    <td data-title="<?=$this->lang->line('leaveapply_no_of_day')?>">
                        <?=$leaveapply->leave_days?>
                    </td>
                    <?php if(permissionChecker('leaveapply_edit') || permissionChecker('leaveapply_delete') || permissionChecker('leaveapply_view')) { ?>
                        <td data-title="<?=$this->lang->line('leaveapply_action')?>">
                            <?=btn_view('leaveapply/view/'.$leaveapply->leaveapplicationID, $this->lang->line('leaveapply_view'))?>
                            <?=($leaveapply->status==NULL) ? btn_edit('leaveapply/edit/'.$leaveapply->leaveapplicationID, $this->lang->line('leaveapply_edit')) : ''?>
                            <?=($leaveapply->status==NULL) ? btn_delete('leaveapply/delete/'.$leaveapply->leaveapplicationID, $this->lang->line('leaveapply_delete')) : ''?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>