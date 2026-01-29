<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-registration"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url("registration/index/".$displayID)?>"><?=$this->lang->line('menu_registration')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('registration_edit')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class='fa fa-edit'></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group capture <?=form_error('photo') ? 'text-danger' : '' ?>">
                                <div class="main-capture">
                                    <div class="single-capture">
                                        <video id="video" autoplay></video>                                        
                                    </div>
                                    <div class="single-capture">
                                        <canvas id="canvas"></canvas>
                                        <input type="hidden" id="image" name="photo" value="<?=$setPhoto?>" />
                                    </div>
                                </div>
                                <button type="button" id="snap" class="btn btn-success"><?=$this->lang->line('registration_picture_capture')?></button>
                                <span><?=form_error('photo')?></span>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('registration_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $patient->name)?>">
                                <span><?=form_error('name')?></span>
                            </div>

                            <div class="form-group <?=form_error('guardianname') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="guardianname"><?=$this->lang->line('registration_guardianname')?></label>
                                <input type="text" class="form-control <?=form_error('guardianname') ? 'is-invalid' : '' ?>" id="guardianname" name="guardianname"  value="<?=set_value('guardianname', $patient->guardianname)?>">
                                <span><?=form_error('guardianname')?></span>
                            </div>

                            <div class="form-group <?=form_error('gender') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="gender"><?=$this->lang->line('registration_gender')?><span class="text-danger"> *</span></label>
                                    <?php
                                    $genderArray['0'] ='— '.$this->lang->line('registration_please_select').' —';
                                    $genderArray['1'] = $this->lang->line('registration_male');
                                    $genderArray['2']  = $this->lang->line('registration_female');

                                    $errorClass = form_error('gender') ? 'is-invalid' : '';
                                    echo form_dropdown('gender', $genderArray,  set_value('gender', $patient->gender), ' class="form-control select2 '.$errorClass.'" ')?>
                                <span><?=form_error('gender')?></span>
                            </div>

                            <div class="form-group <?=form_error('age_day') ? 'text-danger' : '' ?> <?=form_error('age_month') ? 'text-danger' : '' ?> <?=form_error('age_year') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('registration_age')?></label>
                                <div class="input-group">
                                    <input placeholder="dd ex: 1" type="number" max="30" min="0" class="form-control <?=form_error('age_day') ? 'is-invalid' : '' ?>" id="age_day" name="age_day"  value="<?=set_value('age_day', ($patient->age_day == 0) ? '' : $patient->age_day)?>">
                                    <input placeholder="mm ex: 11" type="number" max="11" min="0" class="form-control <?=form_error('age_month') ? 'is-invalid' : '' ?>" id="age_month" name="age_month"  value="<?=set_value('age_month', ($patient->age_month == 0) ? '' : $patient->age_month)?>">
                                    <input placeholder="yyy ex: 75" type="number" max="999" min="0" class="form-control <?=form_error('age_year') ? 'is-invalid' : '' ?>" id="age_year" name="age_year"  value="<?=set_value('age_year', ($patient->age_year == 0) ? '' : $patient->age_year)?>">
                                </div>
                                <span><?=form_error('age_day')?> <?=form_error('age_month')?> <?=form_error('age_year')?></span>
                            </div>

                            <div class="form-group <?=form_error('bloodgroupID') ? 'text-danger' : ''?>">
                                <label for="bloodgroupID"><?=$this->lang->line('registration_bloodgroup')?></label>
                                <?php
                                    $bloodgroupArray['0'] = '— '.$this->lang->line('registration_please_select').' —';
                                    if(inicompute($bloodgroups)) {
                                        foreach ($bloodgroups as $key => $bloodgroup) {
                                            $bloodgroupArray[$bloodgroup->bloodgroupID] = $bloodgroup->bloodgroup;
                                        }
                                    }
                                    $errorClass = form_error('bloodgroupID') ? 'is-invalid' : '';
                                    echo form_dropdown('bloodgroupID', $bloodgroupArray,  set_value('bloodgroupID', $patient->bloodgroupID), 'id="bloodgroupID" class="form-control select2 '.$errorClass.'"');
                                ?>
                                <span><?=form_error('bloodgroupID')?></span>
                            </div>

                            <div class="form-group <?=form_error('maritalstatus') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="maritalstatus"><?=$this->lang->line('registration_marital_status')?></label>
                                    <?php
                                    $maritalstatusArray['0'] ='— '.$this->lang->line('registration_please_select').' —';
                                    $maritalstatusArray['1'] = $this->lang->line('registration_single');
                                    $maritalstatusArray['2'] = $this->lang->line('registration_married');
                                    $maritalstatusArray['3'] = $this->lang->line('registration_separated');
                                    $maritalstatusArray['4'] = $this->lang->line('registration_divorced');

                                    $errorClass = form_error('maritalstatus') ? 'is-invalid' : '';
                                    echo form_dropdown('maritalstatus', $maritalstatusArray,  set_value('maritalstatus', $patient->maritalstatus), ' class="form-control select2 '.$errorClass.'" ')?>
                                <span><?=form_error('maritalstatus')?></span>
                            </div>

                            <div class="form-group <?=form_error('phone') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="phone"><?=$this->lang->line('registration_phone')?></label>
                                <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : '' ?>" id="phone" name="phone"  value="<?=set_value('phone', $patient->phone)?>">
                                <span><?=form_error('phone')?></span>
                            </div>

                            <div class="form-group <?=form_error('email') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="email"><?=$this->lang->line('registration_email')?> </label>
                                <input type="text" class="form-control <?=form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email"  value="<?=set_value('email', $user->email)?>">
                                <span><?=form_error('email')?></span>
                            </div>

                            <div class="form-group <?=form_error('address') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="address"><?=$this->lang->line('registration_address')?></label>
                                <textarea class="form-control <?=form_error('address') ? 'is-invalid' : '' ?>" name="address" rows="3" ><?=set_value('address', $patient->address)?></textarea>
                                <span><?=form_error('address')?></span>
                            </div>

                            <div class="form-group <?=form_error('username') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="username"><?=$this->lang->line('registration_username')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('username') ? 'is-invalid' : ''?>" name="username" id="username" value="<?=set_value('username', $user->username)?>" aria-describedby="newuhid" >
                                <span><?=form_error('username')?></span>
                            </div>

                            <div class="form-group <?=form_error('password') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="password"><?=$this->lang->line('registration_password')?> (<?=$this->lang->line('registration_default')?> : <?=$generalsettings->patient_password?>) <span class="text-danger">*</span></label>
                                <input type="password" class="form-control <?=form_error('password') ? 'is-invalid' : '' ?>" id="password" name="password"  value="<?=set_value('password', $generalsettings->patient_password)?>">
                                <span><?=form_error('password')?></span>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('registration_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class='fa fa-table'></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('registration/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>