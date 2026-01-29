<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('leaveapplication_slno')?></th>
                <th><?=$this->lang->line('leaveapplication_application_to')?></th>
                <th><?=$this->lang->line('leaveapplication_designation')?></th>
                <th><?=$this->lang->line('leaveapplication_category')?></th>
                <th><?=$this->lang->line('leaveapplication_no_of_day')?></th>
                <th><?=$this->lang->line('leaveapplication_status')?></th>
                <th><?=$this->lang->line('leaveapplication_action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($leaveapplications)) { foreach($leaveapplications as $leaveapplication) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('leaveapplication_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('leaveapplication_application_to')?>"><?=isset($users[$leaveapplication->create_userID]) ? $users[$leaveapplication->create_userID]->name : ''?></td>
                    <td data-title="<?=$this->lang->line('leaveapplication_designation')?>"><?=isset($users[$leaveapplication->create_userID]) ? (isset($designations[$users[$leaveapplication->create_userID]->designationID]) ? $designations[$users[$leaveapplication->create_userID]->designationID] : '') : ''?></td>
                    <td data-title="<?=$this->lang->line('leaveapplication_category')?>"><?=isset($leavecategorys[$leaveapplication->leavecategoryID]) ? $leavecategorys[$leaveapplication->leavecategoryID]->leavecategory : ''?></td>
                    <td data-title="<?=$this->lang->line('leaveapplication_no_of_day')?>">
                        <?=$leaveapplication->leave_days?>
                    </td>
                    <td data-title="<?=$this->lang->line('leaveapplication_status')?>">
                        <?php if($leaveapplication->status == NULL) {
                            echo "<span class='btn btn-warning btn-custom mrg'>".$this->lang->line('leaveapplication_pending')."</span>";
                        } elseif($leaveapplication->status == 1) {
                            echo "<span class='btn btn-success btn-custom mrg'>".$this->lang->line('leaveapplication_approved')."</span>";
                        } else {
                            echo "<span class='btn btn-danger btn-custom mrg'>".$this->lang->line('leaveapplication_declined')."</span>";
                        } ?>
                    </td>
                    <td data-title="<?=$this->lang->line('leaveapplication_action')?>">
                        <?php if(permissionChecker('leaveapplication')) { ?>
                            <?=btn_view_show('leaveapplication/view/'.$leaveapplication->leaveapplicationID, $this->lang->line('leaveapplication_view'))?>
                        <?php } ?>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>