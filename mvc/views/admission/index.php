<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-admission"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_admission')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('admission_add')) { ?>
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
                                <?php if($this->data['loginroleID'] != 3) { ?>
                                    <div class="form-group <?=form_error('uhid') ? 'text-danger' : ''?>">
                                        <label for="uhid"><?=$this->lang->line('admission_uhid')?> <span class="text-danger">*</span></label>
                                        <?php
                                            $patientArray['0'] = '— '.$this->lang->line('admission_please_select').' —';
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

                                <div class="form-group <?=form_error('admissiondate') ? 'text-danger' : ''?>">
                                    <label for="admissiondate"><?=$this->lang->line('admission_admissiondate')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker <?=form_error('admissiondate') ? 'is-invalid' : ''?>" name="admissiondate" id="admissiondate" autocomplete="off" value="<?=set_value('admissiondate')?>"/>
                                    <span><?=form_error('admissiondate')?></span>
                                </div>
                                
                                <div class="form-group <?=form_error('case') ? 'text-danger' : ''?>">
                                    <label for="case"><?=$this->lang->line('admission_case')?></label>
                                    <input type="text" class="form-control <?=form_error('case') ? 'is-invalid' : ''?>" name="case" id="case" value="<?=set_value('case')?>"/>
                                    <span><?=form_error('case')?></span>
                                </div>

                                <div class="form-group <?=form_error('casualty') ? 'text-danger' : ''?>">
                                    <label for="casualty"><?=$this->lang->line('admission_casualty')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $casualtyArray['1'] = $this->lang->line('admission_yes');
                                        $casualtyArray['0'] = $this->lang->line('admission_no');
                                        $errorClass = form_error('casualty') ? 'is-invalid' : '';
                                        echo form_dropdown('casualty', $casualtyArray,  set_value('casualty', 0), 'id="casualty" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('casualty')?></span>
                                </div>

                                <div class="form-group <?=form_error('oldpatient') ? 'text-danger' : ''?>">
                                    <label for="oldpatient"><?=$this->lang->line('admission_oldpatient')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $visitedArray['0'] = $this->lang->line('admission_yes');
                                        $visitedArray['1'] = $this->lang->line('admission_no');
                                        $errorClass = form_error('oldpatient') ? 'is-invalid' : '';
                                        echo form_dropdown('oldpatient', $visitedArray,  set_value('oldpatient', 1), 'id="oldpatient" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('oldpatient')?></span>
                                </div>

                                <?php if($this->data['loginroleID'] != 3) { ?>
                                    <div class="form-group <?=form_error('tpaID') ? 'text-danger' : ''?>">
                                        <label for="tpaID"><?=$this->lang->line('admission_tpa')?></label>
                                        <?php
                                            $tpaArray['0'] = '— '.$this->lang->line('admission_please_select').' —';
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
                                    <label for="reference"><?=$this->lang->line('admission_reference')?></label>
                                    <input type="text" class="form-control <?=form_error('reference') ? 'is-invalid' : ''?>" name="reference" id="reference" value="<?=set_value('reference')?>"/>
                                    <span><?=form_error('reference')?></span>
                                </div>

                                <div class="form-group <?=form_error('symptoms') ? 'text-danger' : ''?>">
                                    <label for="symptoms"><?=$this->lang->line('admission_symptoms')?></label>
                                    <textarea id="symptoms" name="symptoms" class="form-control <?=form_error('symptoms') ? 'is-invalid' : ''?>"><?=set_value('symptoms')?></textarea>
                                    <span><?=form_error('symptoms')?></span>
                                </div>

                                <div class="form-group <?=form_error('allergies') ? 'text-danger' : ''?>">
                                    <label for="allergies"><?=$this->lang->line('admission_allergies')?></label>
                                    <textarea id="allergies" name="allergies" class="form-control <?=form_error('allergies') ? 'is-invalid' : ''?>"><?=set_value('allergies')?></textarea>
                                    <span><?=form_error('allergies')?></span>
                                </div>

                                <div class="form-group <?=form_error('doctorID') ? 'text-danger' : ''?>">
                                    <label for="doctorID"><?=$this->lang->line('admission_doctor')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $doctorArray['0'] = '— '.$this->lang->line('admission_please_select').' —';
                                        if(inicompute($doctors)) {
                                            foreach ($doctors as $doctor) {
                                                if($doctor->status == 1 && $doctor->delete_at == 0) {
                                                    $doctorArray[$doctor->userID] = $doctor->name;
                                                }
                                            }
                                        }
                                        $errorClass = form_error('doctorID') ? 'is-invalid' : '';
                                        echo form_dropdown('doctorID', $doctorArray,  set_value('doctorID'), 'id="doctorID" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('doctorID')?></span>
                                </div>

                                <?php if($this->data['loginroleID'] != 3) { ?>
                                    <div class="form-group <?=form_error('creditlimit') ? 'text-danger' : ''?>">
                                        <label for="creditlimit"><?=$this->lang->line('admission_creditlimit')?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?=form_error('creditlimit') ? 'is-invalid' : ''?>" name="creditlimit" id="creditlimit" value="<?=set_value('creditlimit', $generalsettings->patient_credit_limit)?>"/>
                                        <span><?=form_error('creditlimit')?></span>
                                    </div>
                                <?php } ?>

                                <div class="form-group <?=form_error('wardID') ? 'text-danger' : ''?>">
                                    <label for="wardID"><?=$this->lang->line('admission_ward')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $wardArray['0'] = '— '.$this->lang->line('admission_please_select').' —';
                                        if(inicompute($wards)) {
                                            foreach($wards as $ward) {
                                                $wardArray[$ward->wardID] = $ward->name .' - '.$ward->rname;
                                            }
                                        }
                                        $errorClass = form_error('wardID') ? 'is-invalid' : '';
                                        echo form_dropdown('wardID', $wardArray,  set_value('wardID'), 'id="wardID" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('wardID')?></span>
                                </div>

                                <div class="form-group <?=form_error('bedID') ? 'text-danger' : ''?>">
                                    <label for="bedID"><?=$this->lang->line('admission_bed')?> <span class="text-danger">*</span></label>
                                    <?php
                                        $bedArray['0'] = '— '.$this->lang->line('admission_please_select').' —';
                                        if(inicompute($beds)) {
                                            foreach($beds as $bed) {
                                                $bedArray[$bed->bedID] = $bed->name;
                                            }
                                        }
                                        $errorClass = form_error('bedID') ? 'is-invalid' : '';
                                        echo form_dropdown('bedID', $bedArray,  set_value('bedID'), 'id="bedID" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('bedID')?></span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('admission_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('admission_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('admission/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
