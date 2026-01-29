<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-appointment"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_appointment')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('appointment_add')) { ?>
                <div class="col-sm-4">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="header-block">
                                <p class="title"><i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                            </div>
                        </div>
                        <form role="form" method="POST" id="appointmentForm">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <?php if($loginroleID != 3) { ?>
                                    <div class="form-group <?=form_error('uhid') ? 'text-danger' : ''?>">
                                        <label for="uhid"><?=$this->lang->line('appointment_uhid')?> <span class="text-danger">*</span></label>
                                        <?php
                                            $patientArray['0'] = '— '.$this->lang->line('appointment_please_select').' —';
                                            if(inicompute($patients)) {
                                                foreach ($patients as $patient) {
                                                    $patientArray[$patient->patientID] = $patient->patientID.' - '.$patient->name;
                                                }
                                            }
                                            $errorClass = form_error('uhid') ? 'is-invalid' : '';
                                            echo form_dropdown('uhid', $patientArray,  set_value('uhid'), 'id="uhid" class="form-control select2 '.$errorClass.'"');
                                        ?>
                                        <span><?=form_error('uhid')?></span>
                                    </div>
                                <?php } ?>

                                <div class="form-group <?=form_error('appointmentdate') ? 'text-danger' : ''?>">
                                    <label for="appointmentdate"><?=$this->lang->line('appointment_appointmentdate')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker <?=form_error('appointmentdate') ? 'is-invalid' : ''?>" name="appointmentdate" id="appointmentdate" autocomplete="off" value="<?=set_value('appointmentdate')?>" readonly/>
                                    <span><?=form_error('appointmentdate')?></span>
                                </div>
                                
                                <div class="form-group <?=form_error('case') ? 'text-danger' : ''?>">
                                    <label for="case"><?=$this->lang->line('appointment_case')?></label>
                                    <input type="text" class="form-control <?=form_error('case') ? 'is-invalid' : ''?>" name="case" id="case" value="<?=set_value('case')?>"/>
                                    <span><?=form_error('case')?></span>
                                </div>

                                <div class="form-group <?=form_error('casualty') ? 'text-danger' : ''?>">
                                    <label for="casualty"><?=$this->lang->line('appointment_casualty')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $casualtyArray['1'] = $this->lang->line('appointment_yes');
                                        $casualtyArray['0'] = $this->lang->line('appointment_no');
                                        $errorClass = form_error('casualty') ? 'is-invalid' : '';
                                        echo form_dropdown('casualty', $casualtyArray,  set_value('casualty', 0), 'id="casualty" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('casualty')?></span>
                                </div>

                                <div class="form-group <?=form_error('oldpatient') ? 'text-danger' : ''?>">
                                    <label for="oldpatient"><?=$this->lang->line('appointment_oldpatient')?> <span class="text-danger">*</span></label>
                                    <?php
                                    $visitedArray['0'] = $this->lang->line('appointment_yes');
                                    $visitedArray['1'] = $this->lang->line('appointment_no');
                                    $errorClass = form_error('oldpatient') ? 'is-invalid' : '';
                                    echo form_dropdown('oldpatient', $visitedArray,  set_value('oldpatient', 1), 'id="oldpatient" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('oldpatient')?></span>
                                </div>

                                <?php if($loginroleID != 3) { ?>
                                    <div class="form-group <?=form_error('tpaID') ? 'text-danger' : ''?>">
                                        <label for="tpaID"><?=$this->lang->line('appointment_tpa')?></label>
                                        <?php
                                            $tpaArray['0'] = '— '.$this->lang->line('appointment_please_select').' —';
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
                                <?php } ?>

                                <div class="form-group <?=form_error('reference') ? 'text-danger' : ''?>">
                                    <label for="reference"><?=$this->lang->line('appointment_reference')?></label>
                                    <input type="text" class="form-control <?=form_error('reference') ? 'is-invalid' : ''?>" name="reference" id="reference" value="<?=set_value('reference')?>"/>
                                    <span><?=form_error('reference')?></span>
                                </div>

                                <div class="form-group <?=form_error('symptoms') ? 'text-danger' : ''?>">
                                    <label for="symptoms"><?=$this->lang->line('appointment_symptoms')?></label>
                                    <textarea id="symptoms" name="symptoms" class="form-control <?=form_error('symptoms') ? 'is-invalid' : ''?>"><?=set_value('symptoms')?></textarea>
                                    <span><?=form_error('symptoms')?></span>
                                </div>

                                <div class="form-group <?=form_error('allergies') ? 'text-danger' : ''?>">
                                    <label for="allergies"><?=$this->lang->line('appointment_allergies')?></label>
                                    <textarea id="allergies" name="allergies" class="form-control <?=form_error('allergies') ? 'is-invalid' : ''?>"><?=set_value('allergies')?></textarea>
                                    <span><?=form_error('allergies')?></span>
                                </div>

                                <div class="form-group <?=form_error('departmentID') ? 'text-danger' : ''?>">
                                    <label for="departmentID"><?=$this->lang->line('appointment_department')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $departmentArray['0'] = '— '.$this->lang->line('appointment_please_select').' —';
                                        if(inicompute($departments)) {
                                            foreach ($departments as $department) {
                                                $departmentArray[$department->departmentID] = $department->name;
                                            }
                                        }
                                        $errorClass = form_error('departmentID') ? 'is-invalid' : '';
                                        echo form_dropdown('departmentID', $departmentArray,  set_value('departmentID'), 'id="departmentID" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('departmentID')?></span>
                                </div>

                                <div class="form-group <?=form_error('doctorID') ? 'text-danger' : ''?>">
                                    <label for="doctorID"><?=$this->lang->line('appointment_doctor')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $doctorArray['0'] = '— '.$this->lang->line('appointment_please_select').' —';
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

                                <div class="form-group <?=form_error('type') ? 'text-danger' : ''?>">
                                    <label for="type"><?=$this->lang->line('appointment_type')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $typeArray['0'] = '— '.$this->lang->line('appointment_please_select').' —';
                                        if(inicompute($types)) {
                                            foreach ($types as $typeKey => $typeValue) {
                                                $typeArray[$typeKey] = $typeValue;
                                            }
                                        }
                                        $errorClass = form_error('type') ? 'is-invalid' : '';
                                        echo form_dropdown('type', $typeArray,  set_value('type'), 'id="type" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('type')?></span>
                                </div>

                                <div class="form-group <?=form_error('amount') ? 'text-danger' : ''?>">
                                    <label for="amount"><?=$this->lang->line('appointment_amount')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('amount') ? 'is-invalid' : ''?>" name="amount" id="amount" value="<?=set_value('amount')?>" readonly/>
                                    <span><?=form_error('amount')?></span>
                                </div>

                                <div class="form-group <?=form_error('paymentstatus') ? 'text-danger' : ''?>">
                                    <label for="paymentstatus"><?=$this->lang->line('appointment_payment')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $paymentStatus['0'] = '— '.$this->lang->line('appointment_please_select').' —';
                                        $paymentStatus['1'] = $this->lang->line('appointment_paid');
                                        $paymentStatus['2'] = $this->lang->line('appointment_due');
                                        $errorClass = form_error('paymentstatus') ? 'is-invalid' : '';
                                        echo form_dropdown('paymentstatus', $paymentStatus,  set_value('paymentstatus'), 'id="paymentstatus" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('paymentstatus')?></span>
                                </div>

                                <div id="paymentmethodDiv" class="form-group <?=form_error('paymentmethodID') ? 'text-danger' : ''?>">
                                    <label for="paymentmethodID"><?=$this->lang->line('appointment_paymentmethod')?> <span class="text-danger">*</span></label>
                                    <?php

                                        $errorClass = form_error('paymentmethodID') ? 'is-invalid' : '';
                                        echo form_dropdown('paymentmethodID', $paymentmethodArray,  set_value('paymentmethodID'), 'id="paymentmethodID" class="form-control select2 '.$errorClass.'"');
                                    ?>

                                    <span><?=form_error('paymentmethodID')?></span>
                                </div>

                                <?php
                                    if(inicompute($paymentViews)) {
                                        foreach ($paymentViews as $paymentView) {
                                            $this->load->view($paymentView);
                                        }
                                    }
                                ?>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('appointment_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('appointment_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('appointment/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
