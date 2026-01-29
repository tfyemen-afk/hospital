<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-patient"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_patient')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?=($activelisttab) ? 'active' : ''?>" id="list_tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true"><i class="fa fa-table"></i> <?=$this->lang->line('panel_list')?></a>
                    </li>

                    <?php if(permissionChecker('patient_add')) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?=($activeaddtab) ? 'active' : ''?>" id="add_tab" data-toggle="tab" href="#add" role="tab" aria-controls="add" aria-selected="false"><i class="fa fa-plus-square-o"></i> <?=$this->lang->line('panel_add')?></a>
                        </li>
                    <?php } ?>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade bg-color <?=($activelisttab) ? 'show active' : ''?>" id="list" role="tabpanel" aria-labelledby="list_tab">
                        <?php $this->load->view('patient/table')?>
                    </div>

                    <?php if(permissionChecker('patient_add')) { ?>
                        <div class="tab-pane fade bg-color <?=($activeaddtab) ? 'show active' : ''?>" id="add" role="tabpanel" aria-labelledby="add_tab">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <h6>
                                                    <?=$this->lang->line('patient_personal_information')?>
                                                </h6> 
                                            </div>

                                            <div class="form-group col-sm-6 <?=form_error('name') ? 'text-danger' : ''?>">
                                                <label for="name"><?=$this->lang->line('patient_name')?> <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : ''?>" name="name" id="name" value="<?=set_value('name')?>"/>
                                                <span><?=form_error('name')?></span>
                                            </div>

                                            <div class="form-group col-sm-6 <?=form_error('guardianname') ? 'text-danger' : ''?>">
                                                <label for="guardianname"><?=$this->lang->line('patient_guardianname')?></label>
                                                <input type="text" class="form-control <?=form_error('guardianname') ? 'is-invalid' : ''?>" name="guardianname" id="guardianname" value="<?=set_value('guardianname')?>"/>
                                                <span><?=form_error('guardianname')?></span>
                                            </div>

                                            <div class="form-group col-sm-3 <?=form_error('gender') ? 'text-danger' : ''?>">
                                                <label for="gender"><?=$this->lang->line('patient_gender')?> <span class="text-danger">*</span></label>
                                                <?php
                                                    $genderArray['0'] = '— '.$this->lang->line('patient_please_select').' —';
                                                    $genderArray['1'] = $this->lang->line('patient_male');
                                                    $genderArray['2'] = $this->lang->line('patient_female');
                                                    $errorClass = form_error('gender') ? 'is-invalid' : '';
                                                    echo form_dropdown('gender', $genderArray,  set_value('gender'), 'id="gender" class="form-control select2 '.$errorClass.'"');
                                                ?>
                                                <span><?=form_error('gender')?></span>
                                            </div>

                                            <div class="form-group col-sm-3 <?=form_error('maritalstatus') ? 'text-danger' : ''?>">
                                                <label for="maritalstatus"><?=$this->lang->line('patient_maritalstatus')?></label>
                                                <?php
                                                    $maritalstatusArray['0'] = '— '.$this->lang->line('patient_please_select').' —';
                                                    $maritalstatusArray['1'] = $this->lang->line('patient_single');
                                                    $maritalstatusArray['2'] = $this->lang->line('patient_married');
                                                    $maritalstatusArray['3'] = $this->lang->line('patient_separated');
                                                    $maritalstatusArray['4'] = $this->lang->line('patient_divorced');
                                                    $errorClass = form_error('maritalstatus') ? 'is-invalid' : '';
                                                    echo form_dropdown('maritalstatus', $maritalstatusArray,  set_value('maritalstatus'), 'id="maritalstatus" class="form-control select2 '.$errorClass.'"');
                                                ?>
                                                <span><?=form_error('maritalstatus')?></span>
                                            </div>

                                            <div class="form-group col-sm-6 <?=form_error('phone') ? 'text-danger' : ''?>">
                                                <label for="phone"><?=$this->lang->line('patient_phone')?></label>
                                                <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : ''?>" name="phone" id="phone" value="<?=set_value('phone')?>"/>
                                                <span><?=form_error('phone')?></span>
                                            </div>

                                            <div class="form-group col-sm-6 <?=form_error('address') ? 'text-danger' : ''?>">
                                                <label for="address"><?=$this->lang->line('patient_address')?></label>
                                                <input type="text" class="form-control <?=form_error('address') ? 'is-invalid' : ''?>" name="address" id="address" value="<?=set_value('address')?>"/>
                                                <span><?=form_error('address')?></span>
                                            </div>

                                            <div class="form-group col-sm-6 <?=form_error('photo') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="photo"><?=$this->lang->line('patient_photo')?></label>
                                                <div class="custom-file">
                                                    <input type="file" name="photo" class="custom-file-input file-upload-input <?=form_error('photo') ? 'is-invalid' : '' ?>" id="file-upload">
                                                    <label class="custom-file-label label-text-hide" for="file-upload">Choose file</label>
                                                </div>
                                                <span><?=form_error('photo')?></span>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <hr>
                                                <h6>
                                                    <?=$this->lang->line('patient_other_information')?>
                                                </h6>
                                            </div>

                                            <div class="form-group col-sm-6 <?=form_error('age_day') ? 'text-danger' : '' ?> <?=form_error('age_month') ? 'text-danger' : '' ?> <?=form_error('age_year') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="name"><?=$this->lang->line('patient_age')?></label>
                                                <div class="input-group">
                                                    <input placeholder="dd ex: 1" type="number" max="30" min="0" class="form-control <?=form_error('age_day') ? 'is-invalid' : '' ?>" id="age_day" name="age_day"  value="<?=set_value('age_day')?>">
                                                    <input placeholder="mm ex: 11" type="number" max="12" min="0" class="form-control <?=form_error('age_month') ? 'is-invalid' : '' ?>" id="age_month" name="age_month"  value="<?=set_value('age_month')?>">
                                                    <input placeholder="yyy ex: 75" type="number" max="999" min="0" class="form-control <?=form_error('age_year') ? 'is-invalid' : '' ?>" id="age_year" name="age_year"  value="<?=set_value('age_year')?>">
                                                </div>
                                                <span><?=form_error('age_day')?> <?=form_error('age_month')?> <?=form_error('age_year')?></span>
                                            </div>

                                            <div class="form-group col-sm-6 <?=form_error('bloodgroupID') ? 'text-danger' : ''?>">
                                                <label for="bloodgroupID"><?=$this->lang->line('patient_bloodgroup')?></label>
                                                <?php
                                                    $bloodgroupArray['0'] = '— '.$this->lang->line('patient_please_select').' —';

                                                    if(inicompute($bloodgroups)) {
                                                        foreach ($bloodgroups as $key => $bloodgroup) {
                                                            $bloodgroupArray[$bloodgroup->bloodgroupID] = $bloodgroup->bloodgroup;       
                                                        }
                                                    }
                                                    $errorClass = form_error('bloodgroupID') ? 'is-invalid' : '';
                                                    echo form_dropdown('bloodgroupID', $bloodgroupArray,  set_value('bloodgroupID'), 'id="bloodgroupID" class="form-control select2 '.$errorClass.'"');
                                                ?>
                                                <span><?=form_error('bloodgroupID')?></span>
                                            </div>

                                            <div class="form-group col-sm-4 <?=form_error('height') ? 'text-danger' : ''?>">
                                                <label for="height"><?=$this->lang->line('patient_height')?></label>
                                                <input type="text" class="form-control <?=form_error('height') ? 'is-invalid' : ''?>" name="height" id="height" value="<?=set_value('height')?>"/>
                                                <span><?=form_error('height')?></span>
                                            </div>

                                            <div class="form-group col-sm-4 <?=form_error('weight') ? 'text-danger' : ''?>">
                                                <label for="weight"><?=$this->lang->line('patient_weight')?></label>
                                                <input type="text" class="form-control <?=form_error('weight') ? 'is-invalid' : ''?>" name="weight" id="weight" value="<?=set_value('weight')?>"/>
                                                <span><?=form_error('weight')?></span>
                                            </div>

                                            <div class="form-group col-sm-4 <?=form_error('bp') ? 'text-danger' : ''?>">
                                                <label for="bp"><?=$this->lang->line('patient_bp')?></label>
                                                <input type="text" class="form-control <?=form_error('bp') ? 'is-invalid' : ''?>" name="bp" id="bp" value="<?=set_value('bp')?>"/>
                                                <span><?=form_error('bp')?></span>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <hr>
                                                <h6>
                                                    <?=$this->lang->line('patient_treatment_information')?>
                                                </h6>
                                            </div>

                                            <div class="form-group col-sm-6 <?=form_error('symptoms') ? 'text-danger' : ''?>">
                                                <label for="symptoms"><?=$this->lang->line('patient_symptoms')?></label>
                                                <textarea id="symptoms" name="symptoms" class="form-control <?=form_error('symptoms') ? 'is-invalid' : ''?>"><?=set_value('symptoms')?></textarea>
                                                <span><?=form_error('symptoms')?></span>
                                            </div> 

                                            <div class="form-group col-sm-6 <?=form_error('allergies') ? 'text-danger' : ''?>">
                                                <label for="allergies"><?=$this->lang->line('patient_allergies')?></label>
                                                <textarea id="allergies" name="allergies" class="form-control <?=form_error('allergies') ? 'is-invalid' : ''?>"><?=set_value('allergies')?></textarea>
                                                <span><?=form_error('allergies')?></span>
                                            </div>

                                            <div class="form-group col-sm-12 <?=form_error('note') ? 'text-danger' : ''?>">
                                                <label for="note"><?=$this->lang->line('patient_note')?></label>
                                                <textarea id="note" name="note" class="form-control <?=form_error('note') ? 'is-invalid' : ''?>"><?=set_value('note')?></textarea>
                                                <span><?=form_error('note')?></span>
                                            </div>

                                            <div id="logininformationdiv" class="form-group col-sm-12">
                                                <hr>
                                                <h6>
                                                    <?=$this->lang->line('patient_login_information')?>
                                                </h6>
                                            </div>

                                            <div id="usernamediv" class="form-group col-sm-6 <?=form_error('username') ? 'text-danger' : ''?>">
                                                <label for="username"><?=$this->lang->line('patient_username')?> <span class="text-danger">*</span></label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control <?=form_error('username') ? 'is-invalid' : ''?>" name="username" id="username" value="<?=set_value('username', $patientuhid)?>" aria-describedby="newuhid" >
                                                    <div class="input-group-append">
                                                        <span class="input-group-text input-group-point" id="newuhid"><?=$this->lang->line('patient_newuhid')?></span>
                                                    </div>
                                                </div>
                                                <span><?=form_error('username')?></span>
                                            </div>

                                            <div id="emaildiv" class="form-group col-sm-6 <?=form_error('email') ? 'text-danger' : ''?>">
                                                <label for="email"><?=$this->lang->line('patient_email')?></label>
                                                <input type="text" class="form-control <?=form_error('email') ? 'is-invalid' : ''?>" name="email" id="email" value="<?=set_value('email')?>"/>
                                                <span><?=form_error('email')?></span>
                                            </div>

                                            <div id="passworddiv" class="form-group col-sm-6 <?=form_error('password') ? 'text-danger' : ''?>">
                                                <label for="password"><?=$this->lang->line('patient_password')?> (<?=$this->lang->line('patient_default')?> : <?=$generalsettings->patient_password?>) <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control <?=form_error('password') ? 'is-invalid' : ''?>" name="password" id="password" value="<?=set_value('password', $generalsettings->patient_password)?>"/>
                                                <span><?=form_error('password')?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group col-sm-12">
                                            <h6>
                                                <?=$this->lang->line('patient_appointment_information')?>
                                            </h6>
                                        </div>

                                        <div class="form-group col-sm-12 <?=form_error('patienttypeID') ? 'text-danger' : ''?>">
                                            <label for="patienttypeID"><?=$this->lang->line('patient_patienttype')?></label>
                                            <?php
                                                $patienttypeArray['0'] = $this->lang->line('patient_opd');
                                                $patienttypeArray['1'] = $this->lang->line('patient_ipd');
                                                $errorClass = form_error('patienttypeID') ? 'is-invalid' : '';
                                                echo form_dropdown('patienttypeID', $patienttypeArray,  set_value('patienttypeID'), 'id="patienttypeID" class="form-control select2 '.$errorClass.'"');
                                            ?>
                                            <span><?=form_error('patienttypeID')?></span>
                                        </div>

                                        <div id="appointmentdatediv" class="form-group col-sm-12 <?=form_error('appointmentdate') ? 'text-danger' : ''?>">
                                            <label for="appointmentdate"><?=$this->lang->line('patient_appointmentdate')?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control datepicker <?=form_error('appointmentdate') ? 'is-invalid' : ''?>" name="appointmentdate" id="appointmentdate" autocomplete="off" value="<?=set_value('appointmentdate')?>"/>
                                            <span><?=form_error('appointmentdate')?></span>
                                        </div>

                                        <div id="admissiondatediv" class="form-group col-sm-12 <?=form_error('admissiondate') ? 'text-danger' : ''?>">
                                            <label for="admissiondate"><?=$this->lang->line('patient_admissiondate')?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control datepicker <?=form_error('admissiondate') ? 'is-invalid' : ''?>" name="admissiondate" id="admissiondate" autocomplete="off" value="<?=set_value('admissiondate')?>"/>
                                            <span><?=form_error('admissiondate')?></span>
                                        </div>

                                        <div class="form-group col-sm-12 <?=form_error('case') ? 'text-danger' : ''?>">
                                            <label for="case"><?=$this->lang->line('patient_case')?></label>
                                            <input type="text" class="form-control <?=form_error('case') ? 'is-invalid' : ''?>" name="case" id="case" value="<?=set_value('case')?>"/>
                                            <span><?=form_error('case')?></span>
                                        </div>

                                        <div class="form-group col-sm-12 <?=form_error('casualty') ? 'text-danger' : ''?>">
                                            <label for="casualty"><?=$this->lang->line('patient_casualty')?> <span class="text-danger">*</span></label>
                                            <?php
                                                $casualtyArray['1'] = $this->lang->line('patient_yes');
                                                $casualtyArray['0'] = $this->lang->line('patient_no');
                                                $errorClass = form_error('casualty') ? 'is-invalid' : '';
                                                echo form_dropdown('casualty', $casualtyArray,  set_value('casualty', 0), 'id="casualty" class="form-control select2 '.$errorClass.'"');
                                            ?>
                                            <span><?=form_error('casualty')?></span>
                                        </div>

                                        <div class="form-group col-sm-12 <?=form_error('oldpatient') ? 'text-danger' : ''?>">
                                            <label for="oldpatient"><?=$this->lang->line('patient_oldpatient')?> <span class="text-danger">*</span></label>
                                            <?php
                                                $visitedArray['0'] = $this->lang->line('patient_yes');
                                                $visitedArray['1'] = $this->lang->line('patient_no');
                                                $errorClass = form_error('oldpatient') ? 'is-invalid' : '';
                                                echo form_dropdown('oldpatient', $visitedArray,  set_value('oldpatient', 1), 'id="oldpatient" class="form-control select2 '.$errorClass.'"');
                                            ?>
                                            <span><?=form_error('oldpatient')?></span>
                                        </div>

                                        <div class="form-group col-sm-12 <?=form_error('tpaID') ? 'text-danger' : ''?>">
                                            <label for="tpaID"><?=$this->lang->line('patient_tpa')?></label>
                                            <?php
                                                $tpaArray['0'] = '— '.$this->lang->line('patient_please_select').' —';
                                                if(inicompute($tpas)) {
                                                    foreach ($tpas as $tpa) {
                                                        $tpaArray[$tpa->tpaID] = $tpa->name;
                                                    }
                                                }

                                                $errorClass = form_error('tpaID') ? 'is-invalid' : '';
                                                echo form_dropdown('tpaID', $tpaArray,  set_value('tpaID', 0), 'id="tpaID" class="form-control select2 '.$errorClass.'"');
                                            ?>
                                            <span><?=form_error('tpaID')?></span>
                                        </div>

                                        <div class="form-group col-sm-12 <?=form_error('reference') ? 'text-danger' : ''?>">
                                            <label for="reference"><?=$this->lang->line('patient_reference')?></label>
                                            <input type="text" class="form-control <?=form_error('reference') ? 'is-invalid' : ''?>" name="reference" id="reference" value="<?=set_value('reference')?>"/>
                                            <span><?=form_error('reference')?></span>
                                        </div>

                                        <div class="form-group col-sm-12 <?=form_error('doctorID') ? 'text-danger' : ''?>">
                                            <label for="doctorID"><?=$this->lang->line('patient_doctor')?> <span class="text-danger">*</span></label>
                                            <?php
                                                $doctorArray['0'] = '— '.$this->lang->line('patient_please_select').' —';
                                                if(inicompute($doctors)) {
                                                    foreach ($doctors as $doctor) {
                                                        $doctorArray[$doctor->userID] = $doctor->name;
                                                    }
                                                }

                                                $errorClass = form_error('doctorID') ? 'is-invalid' : '';
                                                echo form_dropdown('doctorID', $doctorArray,  set_value('doctorID'), 'id="doctorID" class="form-control select2 '.$errorClass.'"');
                                            ?>
                                            <span><?=form_error('doctorID')?></span>
                                        </div>

                                        <div id="creditlimitdiv" class="form-group col-sm-12 <?=form_error('creditlimit') ? 'text-danger' : ''?>">
                                            <label for="creditlimit"><?=$this->lang->line('patient_creditlimit')?></label>
                                            <input type="text" class="form-control <?=form_error('creditlimit') ? 'is-invalid' : ''?>" name="creditlimit" id="creditlimit" value="<?=set_value('creditlimit', $generalsettings->patient_credit_limit)?>"/>
                                            <span><?=form_error('creditlimit')?></span>
                                        </div>

                                        <div id="amountdiv" class="form-group col-sm-12 <?=form_error('amount') ? 'text-danger' : ''?>">
                                            <label for="amount"><?=$this->lang->line('patient_amount')?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?=form_error('amount') ? 'is-invalid' : ''?>" name="amount" id="amount" value="<?=set_value('amount')?>"/>
                                            <span><?=form_error('amount')?></span>
                                        </div>

                                        <div id="paymentstatusdiv" class="form-group col-sm-12 <?=form_error('paymentstatus') ? 'text-danger' : ''?>">
                                            <label for="paymentstatus"><?=$this->lang->line('patient_payment')?> <span class="text-danger">*</span></label>
                                            <?php
                                            $paymentStatus['0'] = '— '.$this->lang->line('patient_please_select').' —';
                                            $paymentStatus['1'] = $this->lang->line('patient_paid');
                                            $paymentStatus['2'] = $this->lang->line('patient_due');
                                            $errorClass = form_error('paymentstatus') ? 'is-invalid' : '';
                                            echo form_dropdown('paymentstatus', $paymentStatus,  set_value('paymentstatus'), 'id="paymentstatus" class="form-control select2 '.$errorClass.'"');
                                            ?>
                                            <span><?=form_error('paymentstatus')?></span>
                                        </div>

                                        <div id="paymentmethoddiv" class="form-group col-sm-12 <?=form_error('paymentmethodID') ? 'text-danger' : ''?>">
                                            <label for="paymentmethodID"><?=$this->lang->line('patient_paymentmethod')?> <span class="text-danger">*</span></label>
                                            <?php
                                                $paymentmethodArray['0'] = '— '.$this->lang->line('patient_please_select').' —';
                                                $paymentmethodArray['1'] = $this->lang->line('patient_cash');
                                                $paymentmethodArray['2'] = $this->lang->line('patient_cheque');
                                                $paymentmethodArray['3'] = $this->lang->line('patient_other');

                                                $errorClass = form_error('paymentmethodID') ? 'is-invalid' : '';
                                                echo form_dropdown('paymentmethodID', $paymentmethodArray,  set_value('paymentmethodID'), 'id="paymentmethodID" class="form-control select2 '.$errorClass.'"');
                                            ?>
                                            <span><?=form_error('paymentmethodID')?></span>
                                        </div>

                                        <div id="warddiv" class="form-group col-sm-12 <?=form_error('wardID') ? 'text-danger' : ''?>">
                                            <label for="wardID"><?=$this->lang->line('patient_ward')?> <span class="text-danger">*</span></label>
                                            <?php
                                                $wardArray['0'] = '— '.$this->lang->line('patient_please_select').' —';
                                                if(inicompute($wards)) {
                                                    foreach ($wards as $ward) {
                                                        $wardArray[$ward->wardID] = $ward->name;
                                                    }
                                                }
                                                $errorClass = form_error('wardID') ? 'is-invalid' : '';
                                                echo form_dropdown('wardID', $wardArray,  set_value('wardID'), 'id="wardID" class="form-control select2 '.$errorClass.'"');
                                            ?>
                                            <span><?=form_error('wardID')?></span>
                                        </div>

                                        <div id="beddiv" class="form-group col-sm-12 <?=form_error('bedID') ? 'text-danger' : ''?>">
                                            <label for="bedID"><?=$this->lang->line('patient_bedno')?> <span class="text-danger">*</span></label>
                                            <?php
                                                $bedArray['0'] = '— '.$this->lang->line('patient_please_select').' —';
                                                if(inicompute($beds)) {
                                                    foreach ($beds as $bed) {
                                                        $bedArray[$bed->bedID] = $bed->name;
                                                    }
                                                }

                                                $errorClass = form_error('bedID') ? 'is-invalid' : '';
                                                echo form_dropdown('bedID', $bedArray,  set_value('bedID'), 'id="bedID" class="form-control select2 '.$errorClass.'"');
                                            ?>
                                            <span><?=form_error('bedID')?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-primary"><?=$this->lang->line('patient_add')?></button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</article>