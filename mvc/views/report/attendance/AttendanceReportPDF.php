<!DOCTYPE html>
<html>
    <head></head>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('attendancereport_report_for')?> - <?=$this->lang->line('attendancereport_attendance')?></h3>
        </div>
        <div>
            <h6 class="pull-left report-pulllabel">
                <?=$this->lang->line('attendancereport_attendance_type')." : ".$attendance_type?>
            </h6>
            <h6 class="pull-right report-pulllabel">
                <?=$this->lang->line('attendancereport_date')." : ".date('d M Y',$date)?>
            </h6>
        </div>
        <div>
            <?php  if(inicompute($users)) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?=$this->lang->line('attendancereport_photo')?></th>
                            <th><?=$this->lang->line('attendancereport_name')?></th>
                            <th><?=$this->lang->line('attendancereport_designation')?></th>
                            <th><?=$this->lang->line('attendancereport_email')?></th>
                            <th><?=$this->lang->line('attendancereport_phone')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; $flag = 0; foreach($users as $user) {

                            $attendanceDay = isset($attendances[$user->userID]) ? $attendances[$user->userID]->$aday : '';
                            if($attendancetype == 'P' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                                continue;
                            } elseif($attendancetype == 'LE' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='P' || $attendanceDay =='L' )) {
                                continue;
                            } elseif($attendancetype == 'L' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='P' )) {
                                continue;
                            } elseif($attendancetype == 'A' && ($attendanceDay == 'P' || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                                continue;
                            } 
                            $flag = 1;
                            $i++;
                        ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><img class="img-responsive" style="height: 28px; width: 28px" src="<?=imagelink($user->photo, '/uploads/user/') ?>"/></td>
                            <td><?=$user->name?></td>
                            <td><?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></td>
                            <td><?=$user->email?></td>
                            <td><?=$user->phone?></td>
                        </tr>
                        <?php } if(!$flag) { ?>
                            <tr>
                                <td data-title="#" colspan="6">
                                    <?=$this->lang->line('attendancereport_data_not_found')?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('attendancereport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>