<!DOCTYPE html>
<html>
    <head></head>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('leaveapplicationreport_report_for')?> - <?=$this->lang->line('leaveapplicationreport_leave_application')?></h3>
        </div>
        <div>
            <?php 
                $leftlabel  = "";
                $rightlabel = "";
                if($from_date && $to_date) { 
                    $leftlabel = $this->lang->line('leaveapplicationreport_from_date')." : ".$label_from_date;;
                } elseif($categoryID) {
                    $leftlabel = $this->lang->line('leaveapplicationreport_category')." : ".$label_category_name;;
                } elseif($roleID) {
                    $leftlabel = $this->lang->line('leaveapplicationreport_role')." : ".(isset($roles[$roleID]) ? $roles[$roleID] : '');
                }

                if($from_date && $to_date) {
                    $rightlabel = $this->lang->line('leaveapplicationreport_to_date')." : ".$label_to_date;
                } elseif($statusID) { 
                    $rightlabel = $this->lang->line('leaveapplicationreport_status')." : ".(isset($statusArray[$statusID]) ? $statusArray[$statusID] : '');
                } elseif((int)$userID) {
                    $rightlabel = $this->lang->line('leaveapplicationreport_user')." : ".(isset($users[$userID]) ? $users[$userID] : '');
                } elseif($from_date) {
                    $rightlabel = $this->lang->line('leaveapplicationreport_from_date')." : ".$label_from_date;
                } 
            ?>
            <?php $f=TRUE; if($leftlabel) { $f=FALSE; ?>
                <h6 class="pull-left report-pulllabel"><?=$leftlabel?></h6>
            <?php } ?>

            <?php if($rightlabel) { ?>
                <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel"><?=$rightlabel?></h6>
            <?php } ?>
        </div>
        <div>
            <?php  if(inicompute($leaveapplications)) { ?>
            <table>
                <thead>
                    <tr>
                        <th><?=$this->lang->line('leaveapplicationreport_slno')?></th>
                        <th><?=$this->lang->line('leaveapplicationreport_appplicant')?></th>
                        <th><?=$this->lang->line('leaveapplicationreport_role')?></th>
                        <th><?=$this->lang->line('leaveapplicationreport_category')?></th>
                        <th><?=$this->lang->line('leaveapplicationreport_date')?></th>
                        <th><?=$this->lang->line('leaveapplicationreport_schedule')?></th>
                        <th><?=$this->lang->line('leaveapplicationreport_days')?></th>
                        <th><?=$this->lang->line('leaveapplicationreport_status')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; foreach($leaveapplications as $leaveapplication) { $i++; ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=isset($users[$leaveapplication->create_userID]) ? $users[$leaveapplication->create_userID] : ''?></td>
                        <td><?=isset($roles[$leaveapplication->create_roleID]) ? $roles[$leaveapplication->create_roleID] : ''?></td>
                        <td><?=$leaveapplication->leavecategory?></td>
                        <td><?=app_date($leaveapplication->create_date)?></td>
                        <td><?=app_date($leaveapplication->from_date)?> - <?=app_date($leaveapplication->to_date)?></td>
                        <td><?=$leaveapplication->leave_days?></td>
                        <td>
                            <?php 
                                if($leaveapplication->status == '1') {
                                    echo $this->lang->line('leaveapplicationreport_approved');
                                } elseif($leaveapplication->status == '0') {
                                    echo $this->lang->line('leaveapplicationreport_declined');
                                } else {
                                    echo $this->lang->line('leaveapplicationreport_pending');
                                }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
                <div class="report-notfound">
                    <?=$this->lang->line('leaveapplicationreport_data_not_found')?>
                </div>
        <?php } ?>
        </div>
    </div>
</body>
</html>