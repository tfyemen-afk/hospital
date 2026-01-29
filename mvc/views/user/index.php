<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-employee"></i> <?=$this->lang->line('panel_title')?></h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_user')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('user_add')) { ?>
                <div class="col-sm-4">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="header-block">
                                <p class="title"> <i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                            </div>
                        </div>
                        <form role="form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="name"><?=$this->lang->line('user_name')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name')?>">
                                    <span><?=form_error('name')?></span>
                                </div>

                                <div class="form-group <?=form_error('designationID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="designationID"><?=$this->lang->line('user_designation')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $designationArray['0'] = $this->lang->line('user_please_select');
                                        if(inicompute($designations)) {
                                            foreach ($designations as $designationID => $designation) {
                                                $designationArray[$designationID] = $designation;
                                            }
                                        }
                                        $errorClass = form_error('designationID') ? 'is-invalid' : '';
                                        echo form_dropdown('designationID', $designationArray,  set_value('designationID'), ' id="designationID" class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('designationID')?></span>
                                </div>

                                <div class="form-group doctorExtra <?=form_error('departmentID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="departmentID"><?=$this->lang->line('user_department')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $departmentArray['0'] = $this->lang->line('user_please_select');
                                        if(inicompute($departments)) {
                                            foreach ($departments as $department) {
                                                $departmentArray[$department->departmentID] = $department->name;
                                            }
                                        }
                                        $errorClass = form_error('departmentID') ? 'is-invalid' : '';
                                        echo form_dropdown('departmentID', $departmentArray,  set_value('departmentID'), ' id="departmentID" class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('departmentID')?></span>
                                </div>

                                <div class="form-group <?=form_error('description') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="description"><?=$this->lang->line('user_description')?></label>
                                    <textarea type="text" class="form-control <?=form_error('description') ? 'is-invalid' : '' ?>" id="description" name="description" ><?=set_value('description')?></textarea>
                                    <span><?=form_error('description')?></span>
                                </div>

                                <div class="form-group <?=form_error('gender') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="gender"><?=$this->lang->line('user_gender')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $genderArray['0'] = $this->lang->line('user_please_select');
                                        $genderArray['1'] = $this->lang->line('user_male');
                                        $genderArray['2']  = $this->lang->line('user_female');

                                        $errorClass = form_error('gender') ? 'is-invalid' : '';
                                        echo form_dropdown('gender', $genderArray,  set_value('gender'), ' class="form-control select2 '.$errorClass.'" ')?>
                                    <span><?=form_error('gender')?></span>
                                </div>

                                <div class="form-group <?=form_error('dob') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="dob"><?=$this->lang->line('user_dob')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('dob') ? 'is-invalid' : '' ?>" id="dob" name="dob"  value="<?=set_value('dob')?>">
                                    <span><?=form_error('dob')?></span>
                                </div>

                                <div class="form-group <?=form_error('religion') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="religion"><?=$this->lang->line('user_religion')?></label>
                                    <input type="text" class="form-control <?=form_error('religion') ? 'is-invalid' : '' ?>" id="religion" name="religion"  value="<?=set_value('religion')?>">
                                    <span><?=form_error('religion')?></span>
                                </div>

                                <div class="form-group <?=form_error('email') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="email"><?=$this->lang->line('user_email')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email"  value="<?=set_value('email')?>">
                                    <span><?=form_error('email')?></span>
                                </div>

                                <div class="form-group <?=form_error('phone') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="phone"><?=$this->lang->line('user_phone')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : '' ?>" id="phone" name="phone"  value="<?=set_value('phone')?>">
                                    <span><?=form_error('phone')?></span>
                                </div>

                                <div class="form-group <?=form_error('address') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="address"><?=$this->lang->line('user_address')?></label>
                                    <textarea type="text" class="form-control <?=form_error('address') ? 'is-invalid' : '' ?>" id="address" name="address"  ><?=set_value('address')?></textarea>
                                    <span><?=form_error('address')?></span>
                                </div>

                                <div class="form-group <?=form_error('jod') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="jod"><?=$this->lang->line('user_jod')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('jod') ? 'is-invalid' : '' ?>" id="jod" name="jod"  value="<?=set_value('jod')?>">
                                    <span><?=form_error('jod')?></span>
                                </div>

                                <div class="doctorExtra">
                                    <div class="form-group <?=form_error('visit_fee') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="visit_fee"><?=$this->lang->line('user_visit_fee')?><span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control <?=form_error('visit_fee') ? 'is-invalid' : '' ?>" id="visit_fee" name="visit_fee"  value="<?=set_value('visit_fee')?>">
                                        <span><?=form_error('visit_fee')?></span>
                                    </div>

                                    <div class="form-group <?=form_error('online_consultation') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="online_consultation"><?=$this->lang->line('user_online_consultation')?><span class="text-danger"> *</span></label>
                                            <?php
                                            $onlineConsultationArray['0'] = $this->lang->line('user_please_select');
                                            $onlineConsultationArray['1'] = $this->lang->line('user_yes');
                                            $onlineConsultationArray['2']  = $this->lang->line('user_no');

                                            $errorClass = form_error('online_consultation') ? 'is-invalid' : '';
                                            echo form_dropdown('online_consultation', $onlineConsultationArray,  set_value('online_consultation'), ' class="form-control select2 '.$errorClass.'" ')?>
                                        <span><?=form_error('online_consultation')?></span>
                                    </div>

                                    <div class="form-group <?=form_error('consultation_fee') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="consultation_fee"><?=$this->lang->line('user_consultation_fee')?><span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control <?=form_error('consultation_fee') ? 'is-invalid' : '' ?>" id="consultation_fee" name="consultation_fee"  value="<?=set_value('consultation_fee')?>">
                                        <span><?=form_error('consultation_fee')?></span>
                                    </div>
                                </div>
                                
                                <div class="form-group <?=form_error('photo') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="photo"><?=$this->lang->line('user_photo')?></label>
                                    <div class="custom-file">
                                        <input type="file" name="photo" class="custom-file-input file-upload-input <?=form_error('photo') ? 'is-invalid' : '' ?>" id="file-upload">
                                        <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('user_choose_file')?></label>
                                    </div>
                                    <span><?=form_error('photo')?></span>
                                </div>


                                <div class="form-group <?=form_error('roleID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="role"><?=$this->lang->line('user_role')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $roleArray['0'] = $this->lang->line('user_please_select');
                                        if(inicompute($roles)) {
                                            foreach ($roles as $roleID => $role) {
                                                $roleArray[$roleID] = $role;
                                            }
                                        }

                                        $errorClass = form_error('roleID') ? 'is-invalid' : '';
                                        echo form_dropdown('roleID', $roleArray, set_value('roleID'), ' class="form-control select2 '.$errorClass.'" ')?>
                                    <span><?=form_error('roleID')?></span>
                                </div>

                                <div class="form-group <?=form_error('status') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="status"><?=$this->lang->line('user_status')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $statusArray['0'] = $this->lang->line('user_please_select');
                                        $statusArray['1'] = $this->lang->line('user_active');
                                        $statusArray['2']  = $this->lang->line('user_block');

                                        $errorClass = form_error('status') ? 'is-invalid' : '';
                                        echo form_dropdown('status', $statusArray,  set_value('status'), ' class="form-control select2 '.$errorClass.'" ')?>
                                    <span><?=form_error('status')?></span>
                                </div>

                                <div class="form-group <?=form_error('username') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="username"><?=$this->lang->line('user_username')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('username') ? 'is-invalid' : '' ?>" id="username" name="username"  value="<?=set_value('username')?>">
                                    <span><?=form_error('username')?></span>
                                </div>

                                <div class="form-group <?=form_error('password') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="password"><?=$this->lang->line('user_password')?><span class="text-danger"> *</span></label>
                                    <input type="password" class="form-control <?=form_error('password') ? 'is-invalid' : '' ?>" id="password" name="password"  value="<?=set_value('password')?>">
                                    <span><?=form_error('password')?></span>
                                </div> 
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('user_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('user_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('user/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>