<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-deathregister"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_deathregister')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('deathregister_add')) { ?>
                <div class="col-sm-4">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="header-block">
                                <p class="title"><i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                            </div>
                        </div>
                        <form  autocomplete="off" role="form" method="POST">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="name"><?=$this->lang->line('deathregister_name_of_deceased')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name')?>">
                                    <span><?=form_error('name')?></span>
                                </div>
                                <div class="form-group <?=form_error('relation') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="relation"><?=$this->lang->line('deathregister_relation_of_deceased')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('relation') ? 'is-invalid' : '' ?>" id="relation" name="relation"  value="<?=set_value('relation')?>">
                                    <span><?=form_error('relation')?></span>
                                </div>
                                <div class="form-group <?=form_error('guardian_name') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="guardian_name"><?=$this->lang->line('deathregister_guardian_name')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('guardian_name') ? 'is-invalid' : '' ?>" id="guardian_name" name="guardian_name"  value="<?=set_value('guardian_name')?>">
                                    <span><?=form_error('guardian_name')?></span>
                                </div>
                                <div class="form-group <?=form_error('permanent_address') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="permanent_address"><?=$this->lang->line('deathregister_permanent_address')?><span class="text-danger"> *</span></label>
                                    <textarea type="text" class="form-control <?=form_error('permanent_address') ? 'is-invalid' : '' ?>" id="permanent_address" name="permanent_address" rows="2"><?=set_value('permanent_address')?></textarea>
                                    <span><?=form_error('permanent_address')?></span>
                                </div>
                                <div class="form-group <?=form_error('gender') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="gender"><?=$this->lang->line('deathregister_gender')?> <span class="text-danger">*</span></label>
                                        <?php
                                        $genderArray['0'] = "— ".$this->lang->line('deathregister_please_select')." —";
                                        $genderArray['1'] = $this->lang->line('deathregister_male');
                                        $genderArray['2'] = $this->lang->line('deathregister_female');
                                        $errorClass = form_error('gender') ? 'is-invalid' : '';
                                        echo form_dropdown('gender', $genderArray,  set_value('gender'), ' class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('gender')?></span>
                                </div>
                                <div class="form-group <?=form_error('age') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="age"><?=$this->lang->line('deathregister_age_of_the_deceased')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('age') ? 'is-invalid' : '' ?>" id="age" name="age"  value="<?=set_value('age')?>">
                                    <span><?=form_error('age')?></span>
                                </div>
                                <div class="form-group <?=form_error('death_date') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="death_date"><?=$this->lang->line('deathregister_date_of_death')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('death_date') ? 'is-invalid' : '' ?>" id="death_date" name="death_date"  value="<?=set_value('death_date')?>">
                                    <span><?=form_error('death_date')?></span>
                                </div>
                                <div class="form-group <?=form_error('nationality') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="nationality"><?=$this->lang->line('deathregister_nationality')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('nationality') ? 'is-invalid' : '' ?>" id="nationality" name="nationality"  value="<?=set_value('nationality')?>">
                                    <span><?=form_error('nationality')?></span>
                                </div>
                                <div class="form-group <?=form_error('death_cause') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="death_cause"><?=$this->lang->line('deathregister_cause_of_death')?> <span class="text-danger"> *</span></label>
                                    <textarea type="text" class="form-control <?=form_error('death_cause') ? 'is-invalid' : '' ?>" id="death_cause" name="death_cause" rows="2"><?=set_value('death_cause')?></textarea>
                                    <span><?=form_error('death_cause')?></span>
                                </div>
                                <div class="form-group <?=form_error('doctorID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="doctorID"><?=$this->lang->line('deathregister_name_of_the_doctor')?> <span class="text-danger"> *</span></label>
                                        <?php
                                        $doctorArray['0'] = "— ".$this->lang->line('deathregister_please_select')." —";
                                        if(inicompute($doctors)) {
                                            foreach($doctors as $doctor) {
                                                $doctorArray[$doctor->userID] = $doctor->name;
                                            }
                                        }
                                        $errorClass = form_error('doctorID') ? 'is-invalid' : '';
                                        echo form_dropdown('doctorID', $doctorArray,  set_value('doctorID'), ' class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('doctorID')?></span>
                                </div>
                                <div class="form-group <?=form_error('confirm_date') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="confirm_date"><?=$this->lang->line('deathregister_death_confirm_date')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('confirm_date') ? 'is-invalid' : '' ?>" id="confirm_date" name="confirm_date"  value="<?=set_value('confirm_date')?>">
                                    <span><?=form_error('confirm_date')?></span>
                                </div>
                                <div class="form-group <?=form_error('patientID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="patientID"><?=$this->lang->line('deathregister_patient')?> </label>
                                        <?php
                                        $patientArray['0'] = "— ".$this->lang->line('deathregister_please_select')." —";
                                        if(inicompute($patients)) {
                                            foreach($patients as $patient) {
                                                $patientArray[$patient->patientID] = $patient->name;
                                            }
                                        }

                                        $errorClass = form_error('patientID') ? 'is-invalid' : '';
                                        echo form_dropdown('patientID', $patientArray,  set_value('patientID'), ' class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('patientID')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('deathregister_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('deathregister_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('deathregister/table')?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>