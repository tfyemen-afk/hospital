<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="view-main-area-top-left">
                        <img class="view-main-area-top-img" src="<?=pdfimagelink($user->photo,'uploads/user')?>" alt="">
                    </div>
                    <div class="view-main-area-top-right">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapply_name')?></div>
                            <div class="single-user-info-value">: <?=$user->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapply_designation')?></div>
                            <div class="single-user-info-value">: <?=inicompute($designation) ? $designation->designation : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapply_gender')?></div>
                            <div class="single-user-info-value">: <?=($user->gender == 1) ? $this->lang->line('leaveapply_male') : $this->lang->line('leaveapply_female') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapply_dob')?></div>
                            <div class="single-user-info-value">: <?=app_date($user->dob)?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapply_phone')?></div>
                            <div class="single-user-info-value">: <?=$user->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapply_schedule')?></td>
                            <td><?=app_date($leaveapply->from_date);?> - <?=app_date($leaveapply->to_date);?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapply_available_leave_day')?></td>
                            <td><?=$availableleavedays;?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapply_leaveday')?></td>
                            <td><?=$leave_days?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapply_category')?></td>
                            <td><?=inicompute($leavecategory) ? $leavecategory->leavecategory : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapply_on_duty_leave')?></td>
                            <td>
                                <?php if($leaveapply->od_status == null || $leaveapply->od_status == 0) { ?>
                                    <?=$this->lang->line('leaveapply_no');?>
                                <?php } else { ?>
                                    <?=$this->lang->line('leaveapply_yes');?>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapply_application_status')?></td>
                            <td>
                                <?php if($leaveapply->status == null) { 
                                    echo $this->lang->line('leaveapply_pending');
                                 } elseif($leaveapply->status == 1) { 
                                    echo $this->lang->line('leaveapply_approved');
                                 } else { 
                                    echo $this->lang->line('leaveapply_declined');
                                } ?>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <p><?=$leaveapply->reason?></p>
                </div>
            </div>
        </div>
    </body>
</html>