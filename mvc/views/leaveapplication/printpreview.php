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
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapplication_name')?></div>
                            <div class="single-user-info-value">: <?=$user->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapplication_designation')?></div>
                            <div class="single-user-info-value">: <?=inicompute($designation) ? $designation->designation : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapplication_gender')?></div>
                            <div class="single-user-info-value">: <?=($user->gender == 1) ? $this->lang->line('leaveapplication_male') : $this->lang->line('leaveapplication_female') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapplication_dob')?></div>
                            <div class="single-user-info-value">: <?=app_date($user->dob)?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('leaveapplication_phone')?></div>
                            <div class="single-user-info-value">: <?=$user->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapplication_schedule')?></td>
                            <td><?=app_date($leaveapplication->from_date);?> - <?=app_date($leaveapplication->to_date);?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapplication_available_leave_day')?></td>
                            <td><?=$availableleavedays;?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapplication_leaveday')?></td>
                            <td><?=$leave_days?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapplication_category')?></td>
                            <td><?=inicompute($leavecategory) ? $leavecategory->leavecategory : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapplication_on_duty_leave')?></td>
                            <td>
                                <?php if($leaveapplication->od_status == null || $leaveapplication->od_status == 0) { ?>
                                    <?=$this->lang->line('leaveapplication_no');?>
                                <?php } else { ?>
                                    <?=$this->lang->line('leaveapplication_yes');?>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('leaveapplication_application_status')?></td>
                            <td>
                                <?php if($leaveapplication->status == null) { 
                                    echo $this->lang->line('leaveapplication_pending');
                                 } elseif($leaveapplication->status == 1) { 
                                    echo $this->lang->line('leaveapplication_approved');
                                 } else { 
                                    echo $this->lang->line('leaveapplication_declined');
                                } ?>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <p><?=$leaveapplication->reason?></p>
                </div>
            </div>
        </div>
    </body>
</html>