<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-operationtheatre"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('operationtheatre/index/'.$displayID)?>"><?=$this->lang->line('menu_operationtheatre')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('operationtheatre_edit')?></li>
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
                            <p class="title"><i class="fa fa-edit"></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form autocomplete="off" role="form" method="POST">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('operation_name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="operation_name"><?=$this->lang->line('operationtheatre_operation_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('operation_name') ? 'is-invalid' : '' ?>" id="operation_name" name="operation_name"  value="<?=set_value('operation_name', $operationtheatre->operation_name)?>">
                                <span><?=form_error('operation_name')?></span>
                            </div>
                            <div class="form-group <?=form_error('operation_type') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="operation_type"><?=$this->lang->line('operationtheatre_operation_type')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('operation_type') ? 'is-invalid' : '' ?>" id="operation_type" name="operation_type"  value="<?=set_value('operation_type', $operationtheatre->operation_type)?>">
                                <span><?=form_error('operation_type')?></span>
                            </div>
                            <div class="form-group <?=form_error('patientID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="patientID"><?=$this->lang->line('operationtheatre_patient')?> <span class="text-danger">*</span></label>
                                    <?php
                                    $patientArray['0'] = "— ".$this->lang->line('operationtheatre_please_select')." —";
                                    if(inicompute($patients)) {
                                        foreach ($patients as $patient) {
                                            $patientArray[$patient->patientID] = $patient->name;
                                        }
                                    }
                                    $errorClass = form_error('patientID') ? 'is-invalid' : '';
                                    echo form_dropdown('patientID', $patientArray,  set_value('patientID', $operationtheatre->patientID), ' class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('patientID')?></span>
                            </div>
                            <div class="form-group <?=form_error('operation_date') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="operation_date"><?=$this->lang->line('operationtheatre_operation_date')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('operation_date') ? 'is-invalid' : '' ?>" id="operation_date" name="operation_date"  value="<?=set_value('operation_date', date('d-m-Y h:i A', strtotime($operationtheatre->operation_date)))?>">
                                <span><?=form_error('operation_date')?></span>
                            </div>
                            <div class="form-group <?=form_error('doctorID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="doctorID"><?=$this->lang->line('operationtheatre_doctor')?> <span class="text-danger">*</span></label>
                                    <?php
                                    $doctorArray['0'] = "— ".$this->lang->line('operationtheatre_please_select')." —";
                                    if(inicompute($doctors)) {
                                        foreach ($doctors as $doctor) {
                                            $doctorArray[$doctor->userID] = $doctor->name;
                                        }
                                    }
                                    $errorClass = form_error('doctorID') ? 'is-invalid' : '';
                                    echo form_dropdown('doctorID', $doctorArray,  set_value('doctorID', $operationtheatre->doctorID), ' id="doctorID" class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('doctorID')?></span>
                            </div>
                            <div class="form-group <?=form_error('assistant_doctor_1') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="assistant_doctor_1"><?=$this->lang->line('operationtheatre_assistant_doctor_1')?></label>
                                    <?php
                                    $assistant1_Array['0'] = "— ".$this->lang->line('operationtheatre_please_select')." —";
                                    if(inicompute($doctors)) {
                                        foreach ($doctors as $doctor) {
                                            if($operationtheatre->doctorID == $doctor->userID) {
                                                continue;   
                                            }
                                            if($operationtheatre->assistant_doctor_2 == $doctor->userID) {
                                                continue;   
                                            }
                                            $assistant1_Array[$doctor->userID] = $doctor->name;
                                        }
                                    }
                                    $errorClass = form_error('assistant_doctor_1') ? 'is-invalid' : '';
                                    echo form_dropdown('assistant_doctor_1', $assistant1_Array,  set_value('assistant_doctor_1', $operationtheatre->assistant_doctor_1), ' id="assistant_doctor_1" class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('assistant_doctor_1')?></span>
                            </div>
                            <div class="form-group <?=form_error('assistant_doctor_2') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="assistant_doctor_2"><?=$this->lang->line('operationtheatre_assistant_doctor_2')?></label>
                                    <?php
                                    $assistant2_Array['0'] = "— ".$this->lang->line('operationtheatre_please_select')." —";
                                    if(inicompute($doctors)) {
                                        foreach ($doctors as $doctor) {
                                            if($operationtheatre->doctorID == $doctor->userID) {
                                                continue;   
                                            }
                                            if($operationtheatre->assistant_doctor_1 == $doctor->userID) {
                                                continue;   
                                            }
                                            $assistant2_Array[$doctor->userID] = $doctor->name;
                                        }
                                    }
                                    $errorClass = form_error('assistant_doctor_2') ? 'is-invalid' : '';
                                    echo form_dropdown('assistant_doctor_2', $assistant2_Array,  set_value('assistant_doctor_2', $operationtheatre->assistant_doctor_2), ' id="assistant_doctor_2" class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('assistant_doctor_2')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('operationtheatre_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('operationtheatre/table')?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>