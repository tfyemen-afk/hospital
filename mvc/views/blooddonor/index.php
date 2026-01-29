<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-blooddonor"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_blooddonor')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('blooddonor_add')) { ?>
                <div class="col-sm-4">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="header-block">
                                <p class="title"><i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                            </div>
                        </div>
                        <form role="form" method="POST">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="name"><?=$this->lang->line('blooddonor_name')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name')?>">
                                    <span><?=form_error('name')?></span>
                                </div>
                                <div class="form-group <?=form_error('gender') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="gender"><?=$this->lang->line('blooddonor_gender')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $genderArray['0'] = '— '.$this->lang->line('blooddonor_please_select').' —';
                                        $genderArray['1'] = $this->lang->line('blooddonor_male');
                                        $genderArray['2'] = $this->lang->line('blooddonor_female');

                                        $errorClass = form_error('gender') ? 'is-invalid' : '';
                                        echo form_dropdown('gender', $genderArray,  set_value('gender'), ' class="form-control select2 '.$errorClass.'" ')?>
                                    <span><?=form_error('gender')?></span>
                                </div>
                                <div class="form-group <?=form_error('age') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="age"><?=$this->lang->line('blooddonor_age')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('age') ? 'is-invalid' : '' ?>" id="age" name="age"  value="<?=set_value('age')?>">
                                    <span><?=form_error('age')?></span>
                                </div>
                                <div class="form-group <?=form_error('bloodgroupID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="bloodgroupID"><?=$this->lang->line('blooddonor_bloodgroupID')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $bloodgroupArray['0'] = '— '.$this->lang->line('blooddonor_please_select').' —';
                                        if(inicompute($bloodgroups)) {
                                            foreach ($bloodgroups as $bloodgroup) {
                                                $bloodgroupArray[$bloodgroup->bloodgroupID] = $bloodgroup->bloodgroup;
                                            }
                                        }
                                        $errorClass = form_error('bloodgroupID') ? 'is-invalid' : '';
                                        echo form_dropdown('bloodgroupID', $bloodgroupArray,  set_value('bloodgroupID'), ' class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('bloodgroupID')?></span>
                                </div>
                                <div class="form-group <?=form_error('phone') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="phone"><?=$this->lang->line('blooddonor_phone')?></label>
                                    <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : '' ?>" id="phone" name="phone"  value="<?=set_value('phone')?>">
                                    <span><?=form_error('phone')?></span>
                                </div>
                                <div class="form-group <?=form_error('email') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="email"><?=$this->lang->line('blooddonor_email')?></label>
                                    <input type="email" class="form-control <?=form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email"  value="<?=set_value('email')?>">
                                    <span><?=form_error('email')?></span>
                                </div>

                                <div class="form-group <?=form_error('patientID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="patientID"><?=$this->lang->line('blooddonor_uhid')?></label>
                                        <?php
                                        $patientArray['0']  = '— '.$this->lang->line('blooddonor_please_select').' —';
                                        if(inicompute($patients)) {
                                            foreach($patients as $patient) {
                                                $patientArray[$patient->patientID]  = $patient->patientID.' - '. $patient->name;
                                            }
                                        }
                                        $errorClass = form_error('patientID') ? 'is-invalid' : '';
                                        echo form_dropdown('patientID', $patientArray,  set_value('patientID'), ' class="form-control select2 '.$errorClass.'" ')?>
                                    <span><?=form_error('patientID')?></span>
                                </div>

                                <div class="form-group <?=form_error('charges') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="charges"><?=$this->lang->line('blooddonor_charges')?> <span class="text-danger">*</span></label>
                                    <input type="charges" class="form-control <?=form_error('charges') ? 'is-invalid' : '' ?>" id="charges" name="charges" value="<?=set_value('charges', '0.00')?>">
                                    <span><?=form_error('charges')?></span>
                                </div>
                                
                                <div class="form-group <?=form_error('lastdonationdate') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="lastdonationdate"><?=$this->lang->line('blooddonor_lastdonationdate')?></label>
                                    <input type="lastdonationdate" class="form-control datepicker <?=form_error('lastdonationdate') ? 'is-invalid' : '' ?> " id="lastdonationdate" name="lastdonationdate"  value="<?=set_value('lastdonationdate')?>">
                                    <span><?=form_error('lastdonationdate')?></span>
                                </div>

                                <div class="form-group <?=form_error('numberofbag') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="numberofbag"><?=$this->lang->line('blooddonor_numberofbag')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $numberofbagArray['0']  = '— '.$this->lang->line('blooddonor_please_select').' —';
                                        $numberofbagArray['1']  = '1';
                                        $numberofbagArray['2']  = '2';
                                        $numberofbagArray['3']  = '3';
                                        $errorClass = form_error('numberofbag') ? 'is-invalid' : '';
                                        echo form_dropdown('numberofbag', $numberofbagArray,  set_value('numberofbag'), ' class="form-control numberofbag select2 '.$errorClass.'" ')?>
                                    <span><?=form_error('numberofbag')?></span>
                                </div>
                                <div id="bagNo">
                                    
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('blooddonor_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('blooddonor_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('blooddonor/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>