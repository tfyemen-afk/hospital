<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-blooddonorreport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_blooddonorreport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('blooddonorreport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('bloodgroupID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="bloodgroupID"><?=$this->lang->line('blooddonorreport_blood_group')?></label>
                            <?php
                            $bloodgroupArray['0'] = '— '.$this->lang->line('blooddonorreport_please_select').' —';
                            if(inicompute($bloodgroups)) {
                                foreach ($bloodgroups as $bloodgroup) {
                                    $bloodgroupArray[$bloodgroup->bloodgroupID] = $bloodgroup->bloodgroup;
                                }
                            }
                            $errorClass = form_error('bloodgroupID') ? 'is-invalid' : '';
                            echo form_dropdown('bloodgroupID', $bloodgroupArray,  set_value('bloodgroupID'), ' id="bloodgroupID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('bloodgroupID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('blooddonorID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="blooddonorID"><?=$this->lang->line('blooddonorreport_donor_name')?></label>
                            <?php
                            $blooddonorArray['0'] = '— '.$this->lang->line('blooddonorreport_please_select').' —';
                            if(inicompute($blooddonors)) {
                                foreach ($blooddonors as $blooddonor) {
                                    $blooddonorArray[$blooddonor->blooddonorID] = $blooddonor->name;
                                }
                            }
                            $errorClass = form_error('blooddonorID') ? 'is-invalid' : '';
                            echo form_dropdown('blooddonorID', $blooddonorArray,  set_value('blooddonorID'), ' id="blooddonorID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('blooddonorID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('patientID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="patientID"><?=$this->lang->line('blooddonorreport_patient_name')?></label>
                            <?php
                            $patientArray['0'] = '— '.$this->lang->line('blooddonorreport_please_select').' —';
                            if(inicompute($patients)) {
                                foreach ($patients as $patient) {
                                    $patientArray[$patient->patientID] = $patient->name;
                                }
                            }
                            $errorClass = form_error('patientID') ? 'is-invalid' : '';
                            echo form_dropdown('patientID', $patientArray,  set_value('patientID'), ' id="patientID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('patientID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('statusID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="statusID"><?=$this->lang->line('blooddonorreport_status')?></label>
                            <?php
                            $statusArray['0'] = '— '.$this->lang->line('blooddonorreport_please_select').' —';
                            $statusArray[1] = $this->lang->line('blooddonorreport_reserve');
                            $statusArray[2] = $this->lang->line('blooddonorreport_release');
                            $errorClass = form_error('statusID') ? 'is-invalid' : '';
                            echo form_dropdown('statusID', $statusArray,  set_value('statusID'), ' id="statusID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('statusID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('bagno') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="bagno"><?=$this->lang->line('blooddonorreport_bag_no')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('bagno') ? 'is-invalid' : '' ?> bagnopicker" id="bagno" name="bagno"  value="<?=set_value('bagno')?>">
                        <span><?=form_error('bagno')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="from_date"><?=$this->lang->line('blooddonorreport_from_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="to_date"><?=$this->lang->line('blooddonorreport_to_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_blooddonorreport" class="btn btn-success get-report-button"> <?=$this->lang->line('blooddonorreport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_blooddonorreport"></div>
</article>