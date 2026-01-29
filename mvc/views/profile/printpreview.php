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
                            <div class="single-user-info-label"><?=$this->lang->line('profile_name')?></div>
                            <div class="single-user-info-value">: <?=$user->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('profile_designation')?></div>
                            <div class="single-user-info-value">: <?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('profile_gender')?></div>
                            <div class="single-user-info-value">: <?=($user->gender == 1) ? $this->lang->line('profile_male') : $this->lang->line('profile_female') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('profile_dob')?></div>
                            <div class="single-user-info-value">: <?=app_date($user->dob)?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('profile_phone')?></div>
                            <div class="single-user-info-value">: <?=$user->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_jod')?></td>
                            <td><?=app_date($user->jod)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_religion')?></td>
                            <td><?=$user->religion?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_email')?></td>
                            <td><?=$user->email?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_address')?></td>
                            <td><?=$user->address?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_description')?></td>
                            <td><?=$user->description?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_status')?></td>
                            <td><?=($user->status == 1) ? "<span class='text-success'>".$this->lang->line('profile_active')."</span>" : "<span class='text-danger'>".$this->lang->line('profile_block')."</span>"?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_role')?></td>
                            <td><?=isset($roles[$user->roleID]) ? $roles[$user->roleID] : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_username')?></td>
                            <td><?=$user->username?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>