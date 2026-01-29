<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-birthregister"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('birthregister/index/'.$displayID)?>"><?=$this->lang->line('menu_birthregister')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('birthregister_edit')?></li>
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
                    <form  autocomplete="off" role="form" method="POST">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('birthregister_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $birthregister->name)?>">
                                <span><?=form_error('name')?></span>
                            </div>
                            <div class="form-group <?=form_error('father_name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="father_name"><?=$this->lang->line('birthregister_father_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('father_name') ? 'is-invalid' : '' ?>" id="father_name" name="father_name"  value="<?=set_value('father_name', $birthregister->father_name)?>">
                                <span><?=form_error('father_name')?></span>
                            </div>
                            <div class="form-group <?=form_error('mother_name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="mother_name"><?=$this->lang->line('birthregister_mother_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('mother_name') ? 'is-invalid' : '' ?>" id="mother_name" name="mother_name"  value="<?=set_value('mother_name', $birthregister->mother_name)?>">
                                <span><?=form_error('mother_name')?></span>
                            </div>
                            <div class="form-group <?=form_error('gender') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="gender"><?=$this->lang->line('birthregister_gender')?> <span class="text-danger">*</span></label>
                                    <?php
                                    $genderArray['0'] = "— ".$this->lang->line('birthregister_please_select')." —";
                                    $genderArray['1'] = $this->lang->line('birthregister_male');
                                    $genderArray['2'] = $this->lang->line('birthregister_female');
                                    $errorClass = form_error('gender') ? 'is-invalid' : '';
                                    echo form_dropdown('gender', $genderArray,  set_value('gender', $birthregister->gender), ' class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('gender')?></span>
                            </div>
                            <div class="form-group <?=form_error('date') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="date"><?=$this->lang->line('birthregister_date')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('date') ? 'is-invalid' : '' ?>" id="date" name="date"  value="<?=set_value('date', date('d-m-Y h:i A', strtotime($birthregister->date)))?>">
                                <span><?=form_error('date')?></span>
                            </div>
                            <div class="form-group <?=form_error('weight') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="weight"><?=$this->lang->line('birthregister_weight')?></label>
                                <input type="text" class="form-control <?=form_error('weight') ? 'is-invalid' : '' ?>" id="weight" name="weight"  value="<?=set_value('weight', $birthregister->weight)?>">
                                <span><?=form_error('weight')?></span>
                            </div>
                            <div class="form-group <?=form_error('length') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="length"><?=$this->lang->line('birthregister_length')?></label>
                                <input type="text" class="form-control <?=form_error('length') ? 'is-invalid' : '' ?>" id="length" name="length"  value="<?=set_value('length', $birthregister->length)?>">
                                <span><?=form_error('length')?></span>
                            </div>
                            <div class="form-group <?=form_error('patientID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="patientID"><?=$this->lang->line('birthregister_patient')?> </label>
                                    <?php
                                    $patientArray['0'] = "— ".$this->lang->line('birthregister_please_select')." —";
                                    if(inicompute($patients)) {
                                        foreach($patients as $patient) {
                                            $patientArray[$patient->patientID] = $patient->name;
                                        }
                                    }

                                    $errorClass = form_error('patientID') ? 'is-invalid' : '';
                                    echo form_dropdown('patientID', $patientArray,  set_value('patientID', $birthregister->patientID), ' class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('patientID')?></span>
                            </div>
                            <div class="form-group <?=form_error('birth_place') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="birth_place"><?=$this->lang->line('birthregister_birth_place')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('birth_place') ? 'is-invalid' : '' ?>" id="birth_place" name="birth_place"  value="<?=set_value('birth_place', $birthregister->birth_place)?>">
                                <span><?=form_error('birth_place')?></span>
                            </div>
                            <div class="form-group <?=form_error('nationality') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="nationality"><?=$this->lang->line('birthregister_nationality')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('nationality') ? 'is-invalid' : '' ?>" id="nationality" name="nationality"  value="<?=set_value('nationality', $birthregister->nationality)?>">
                                <span><?=form_error('nationality')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('birthregister_update')?></button>
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
                        <?php $this->load->view('birthregister/table')?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>