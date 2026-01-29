<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-prescription"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active"><?=$this->lang->line('menu_prescription')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?=$activetab ? 'active' : ''?>" id="list_tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true"><i class="fa fa-table"></i> <?=$this->lang->line('panel_list')?></a>
                    </li>
                    <?php if(permissionChecker('prescription_add')) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?=$activetab ? '' : 'active'?>" id="add_tab" data-toggle="tab" href="#add" role="tab" aria-controls="add" aria-selected="false"><i class="fa fa-plus-square-o"></i> <?=$this->lang->line('panel_add')?></a>
                        </li>
                    <?php } ?>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade bg-color <?=$activetab ? 'show active' : ''?>" id="list" role="tabpanel" aria-labelledby="list_tab">
                        <?php $this->load->view('prescription/table');?>
                    </div>
                    <?php if(permissionChecker('prescription_add')) { ?>
                        <div class="tab-pane fade <?=$activetab ? '' : 'show active'?>" id="add" role="tabpanel" aria-labelledby="add_tab">
                            <form method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card card-custom">
                                                    <div class="card-header">
                                                        <div class="header-block">
                                                            <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('prescription_filter')?></p>
                                                        </div>
                                                    </div>

                                                    <div class="card-block">
                                                        <div class="form-group <?=form_error('patienttypeID') ? 'text-danger' : '' ?>">
                                                            <label class="control-label" for="patienttypeID"><?=$this->lang->line('prescription_patienttype')?><span class="text-danger"> *</span></label>
                                                            <?php
                                                                $errorClass = form_error('patienttypeID') ? 'is-invalid' : '';
                                                                echo form_dropdown('patienttypeID', $patienttypes,  set_value('patienttypeID', $displaytypeID), ' id="patienttypeID" class="form-control select2 '.$errorClass.'" ')
                                                            ?>
                                                            <span id="patienttypeID-error"><?=form_error('patienttypeID')?></span>
                                                        </div>

                                                        <div class="form-group <?=form_error('uhid') ? 'text-danger' : '' ?>">
                                                            <label class="control-label" for="uhid"><?=$this->lang->line('prescription_uhid')?><span class="text-danger"> *</span></label>
                                                            <?php
                                                                $patientArray['0']  = '— '.$this->lang->line('prescription_please_select').' —';
                                                                if(inicompute($patients)) {
                                                                    foreach ($patients as $patient) {
                                                                        $patientArray[$patient->patientID]  = $patient->patientID.' - '.$patient->name;
                                                                    }
                                                                }

                                                                $errorClass = form_error('uhid') ? 'is-invalid' : '';
                                                                echo form_dropdown('uhid', $patientArray,  set_value('uhid', $displayuhID), ' id="uhid" class="form-control select2 '.$errorClass.'" ')?>
                                                            <span id="uhid-error"><?=form_error('uhid')?></span>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <button type="button" class="btn btn-primary" id="searchuhID"><?=$this->lang->line('prescription_search')?></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if(inicompute($patientinfo)) { ?>
                                                <div class="col-sm-12">
                                                    <div class="card card-custom">
                                                        <div class="card-header">
                                                            <div class="header-block">
                                                                <p class="title"> <i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('prescription_treatment_information')?></p>
                                                            </div>
                                                        </div>

                                                        <div class="card-block">
                                                            <div class="form-group <?=form_error('symptoms') ? 'text-danger' : '' ?>">
                                                                <label class="control-label" for="symptoms"><?=$this->lang->line('prescription_symptoms')?></label> <span class="text-danger">*</span>
                                                                <textarea rows="5" class="form-control <?=form_error('symptoms') ? 'is-invalid' : '' ?>" id="symptoms" name="symptoms"><?=set_value('symptoms', $patientinfo->symptoms)?></textarea>
                                                                <span><?=form_error('symptoms')?></span>
                                                            </div>
                                                            <div class="form-group <?=form_error('allergies') ? 'text-danger' : '' ?>">
                                                                <label class="control-label" for="allergies"><?=$this->lang->line('prescription_allergies')?></label>
                                                                <textarea rows="5" class="form-control <?=form_error('allergies') ? 'is-invalid' : '' ?>" id="allergies" name="allergies"><?=set_value('allergies', $patientinfo->allergies)?></textarea>
                                                                <span><?=form_error('allergies')?></span>
                                                            </div>
                                                            <div class="form-group <?=form_error('test') ? 'text-danger' : '' ?>">
                                                                <label class="control-label" for="test"><?=$this->lang->line('prescription_test')?></label>
                                                                <textarea rows="5" class="form-control <?=form_error('test') ? 'is-invalid' : '' ?>" id="test" name="test"><?=set_value('test', $patientinfo->test)?></textarea>
                                                                <span><?=form_error('test')?></span>
                                                            </div>
                                                            <div class="form-group <?=form_error('advice') ? 'text-danger' : '' ?>">
                                                                <label class="control-label" for="advice"><?=$this->lang->line('prescription_advice')?></label>
                                                                <textarea rows="5" class="form-control <?=form_error('advice') ? 'is-invalid' : '' ?>" id="advice" name="advice"><?=set_value('advice')?></textarea>
                                                                <span><?=form_error('advice')?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="col-sm-9">
                                        <div class="card card-custom">
                                            <div class="card-header">
                                                <div class="header-block">
                                                    <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('prescription_filter_data')?></p>
                                                </div>
                                            </div>

                                            <div class="card-block">
                                                <div class="row">
                                                    <?php if(inicompute($patientinfo)) { $this->load->view('prescription/itemtable'); } ?>
                                                </div>
                                            </div>
                                        </div>
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