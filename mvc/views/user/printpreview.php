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
                            <div class="single-user-info-label"><?=$this->lang->line('user_name')?></div>
                            <div class="single-user-info-value">: <?=$user->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('user_designation')?></div>
                            <div class="single-user-info-value">: <?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('user_gender')?></div>
                            <div class="single-user-info-value">: <?=($user->gender == 1) ? $this->lang->line('user_male') : $this->lang->line('user_female') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('user_dob')?></div>
                            <div class="single-user-info-value">: <?=app_date($user->dob)?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('user_phone')?></div>
                            <div class="single-user-info-value">: <?=$user->phone?></div>
                        </div>
                    </div>
                </div>
                
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_jod')?></td>
                            <td><?=app_date($user->jod)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_religion')?></td>
                            <td><?=$user->religion?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_email')?></td>
                            <td><?=$user->email?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_address')?></td>
                            <td><?=$user->address?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_description')?></td>
                            <td><?=$user->description?></td>
                        </tr>
                        <?php if($user->designationID == 3) { ?>
                            <tr>
                                <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_department')?></td>
                                <td><?=$doctorinfo->departmentName?></td>
                            </tr>
                            <tr>
                                <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_visit_fee')?></td>
                                <td><?=app_currency_format($doctorinfo->visit_fee)?></td>
                            </tr>
                            <tr>
                                <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_online_consultation')?></td>
                                <td>
                                    <?=($doctorinfo->online_consultation == 1) ? "<span class='text-success'>".$this->lang->line('user_yes')."</span>" : "<span class='text-success'>".$this->lang->line('user_no')."</span>"?>
                                </td>
                            </tr>
                            <tr>
                                <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_consultation_fee')?></td>
                                <td><?=app_currency_format($doctorinfo->consultation_fee)?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_status')?></td>
                            <td><?=($user->status == 1) ? "<span class='text-success'>".$this->lang->line('user_active')."</span>" : "<span class='text-danger'>".$this->lang->line('user_block')."</span>"?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_role')?></td>
                            <td><?=isset($roles[$user->roleID]) ? $roles[$user->roleID] : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('user_username')?></td>
                            <td><?=$user->username?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>