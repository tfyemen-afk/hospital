<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-leaveapply"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
              <ol class="breadcrumb themebreadcrumb pull-right">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_leaveapply')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('leaveapply_add')) { ?>
                <div class="col-sm-4">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="header-block">
                                <p class="title"><i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                            </div>
                        </div>
                        <form role="form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <div class="form-group <?=form_error('roleID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="roleID"><?=$this->lang->line('leaveapply_role')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $roleArray['0'] = $this->lang->line('leaveapply_please_select');
                                        if(inicompute($applyroles)) {
                                            foreach ($applyroles as $applyrole) {
                                                $roleArray[$applyrole->roleID] = $applyrole->role;
                                            }
                                        }
                                        $errorClass = form_error('roleID') ? 'is-invalid' : '';
                                        echo form_dropdown('roleID', $roleArray,  set_value('roleID'), ' class="form-control select2 '.$errorClass.'" id="roleID" ');
                                    ?>
                                    <span><?=form_error('roleID')?></span>
                                </div>
                                <div class="form-group <?=form_error('applicationto_userID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="applicationto_userID"><?=$this->lang->line('leaveapply_application_to')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $userArray['0'] = $this->lang->line('leaveapply_please_select');
                                        if(inicompute($users)) {
                                            $userID = $this->session->userdata('loginuserID');
                                            foreach($users as $user) {
                                                if (($userID != $user->userID) || ($userID == 1)) {
                                                    $userArray[$user->userID] = $user->name;
                                                }
                                            }
                                        }
                                        $errorClass = form_error('applicationto_userID') ? 'is-invalid' : '';
                                        echo form_dropdown('applicationto_userID', $userArray,  set_value('applicationto_userID'), ' class="form-control select2 '.$errorClass.'" id="applicationto_userID" ');
                                    ?>
                                    <span><?=form_error('applicationto_userID')?></span>
                                </div>
                                <div class="form-group <?=form_error('leavecategoryID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="leavecategoryID"><?=$this->lang->line('leaveapply_category')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $leaveassignArray['0'] = $this->lang->line('leaveapply_please_select');
                                        if(inicompute($leaveassigns)) {
                                            foreach ($leaveassigns as $leaveassign) {
                                                $leaveassignArray[$leaveassign->leavecategoryID] = isset($leavecategorys[$leaveassign->leavecategoryID]) ? $leavecategorys[$leaveassign->leavecategoryID]->leavecategory .' ( '. $leaveassign->leaveassignday.' )' : '';
                                            }
                                        }

                                        $errorClass = form_error('leavecategoryID') ? 'is-invalid' : '';
                                        echo form_dropdown('leavecategoryID', $leaveassignArray,  set_value('leavecategoryID'), ' class="form-control select2 '.$errorClass.'" ');
                                    ?>
                                    <span><?=form_error('leavecategoryID')?></span>
                                </div>
                                <div class="form-group <?=form_error('schedule') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="schedule"><?=$this->lang->line('leaveapply_schedule')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control daterange <?=form_error('schedule') ? 'is-invalid' : '' ?>" id="schedule" name="schedule"  value="<?=set_value('schedule')?>">
                                    <span><?=form_error('schedule')?></span>
                                </div>
                                <div class="form-group <?=form_error('reason') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="reason"><?=$this->lang->line('leaveapply_reason')?> <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="reason" class="form-control <?=form_error('reason') ? 'is-invalid' : '' ?>" cols="30" rows="5"></textarea>
                                    <span><?=form_error('reason')?></span>
                                </div>
                                <div class="form-group <?=form_error('attachment') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="attachment"><?=$this->lang->line('leaveapply_attachment')?> </label>
                                    <div class="custom-file">
                                        <input type="file" name="attachment" class="custom-file-input file-upload-input <?=form_error('attachment') ? 'is-invalid' : '' ?>" id="file-upload">
                                        <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('leaveapply_choose_file')?></label>
                                    </div>
                                    <span><?=form_error('attachment')?></span>
                                </div>
                                <div class="form-group <?=form_error('od_status') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="od_status"><?=$this->lang->line('leaveapply_on_duty_leave')?></label> <br/>
                                    <input type="checkbox" class="<?=form_error('od_status') ? 'is-invalid' : '' ?>" id="od_status" name="od_status" value="1" <?=set_checkbox('od_status', '1'); ?>>
                                    <span><?=form_error('od_status')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('leaveapply_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('leaveapply_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('leaveapply/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>