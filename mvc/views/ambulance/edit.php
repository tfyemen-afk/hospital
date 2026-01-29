<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-ambulance"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('ambulance/index')?>"><?=$this->lang->line('menu_ambulance')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('ambulance_edit')?></li>
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
                    <form role="form" method="POST">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('ambulance_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $ambulance->name)?>">
                                <span><?=form_error('name')?></span>
                            </div>
                            <div class="form-group <?=form_error('number') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="number"><?=$this->lang->line('ambulance_number')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('number') ? 'is-invalid' : '' ?>" id="number" name="number"  value="<?=set_value('number', $ambulance->number)?>">
                                <span><?=form_error('number')?></span>
                            </div>
                            <div class="form-group <?=form_error('model') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="model"><?=$this->lang->line('ambulance_model')?></label>
                                <input type="text" class="form-control <?=form_error('model') ? 'is-invalid' : '' ?>" id="model" name="model"  value="<?=set_value('model', $ambulance->model)?>">
                                <span><?=form_error('model')?></span>
                            </div>
                            <div class="form-group <?=form_error('color') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="color"><?=$this->lang->line('ambulance_color')?></label>
                                <input type="text" class="form-control <?=form_error('color') ? 'is-invalid' : '' ?>" id="color" name="color"  value="<?=set_value('color', $ambulance->color)?>">
                                <span><?=form_error('color')?></span>
                            </div>
                            <div class="form-group <?=form_error('cc') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="cc"><?=$this->lang->line('ambulance_cc')?></label>
                                <input type="text" class="form-control <?=form_error('cc') ? 'is-invalid' : '' ?>" id="cc" name="cc"  value="<?=set_value('cc', $ambulance->cc)?>">
                                <span><?=form_error('cc')?></span>
                            </div>
                            <div class="form-group <?=form_error('weight') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="weight"><?=$this->lang->line('ambulance_weight')?></label>
                                <input type="text" class="form-control <?=form_error('weight') ? 'is-invalid' : '' ?>" id="weight" name="weight"  value="<?=set_value('weight', $ambulance->weight)?>">
                                <span><?=form_error('weight')?></span>
                            </div>
                            <div class="form-group <?=form_error('fueltype') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="fueltype"><?=$this->lang->line('ambulance_fuel_type')?> <span class="text-danger">*</span></label>
                                    <?php
                                    $fueltypeArray['0'] = "— ".$this->lang->line('ambulance_please_select')." —";
                                    $fueltypeArray['1'] = $this->lang->line('ambulance_cng');
                                    $fueltypeArray['2'] = $this->lang->line('ambulance_diesel');
                                    
                                    $errorClass = form_error('fueltype') ? 'is-invalid' : '';
                                    echo form_dropdown('fueltype', $fueltypeArray,  set_value('fueltype', $ambulance->fueltype), ' class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('fueltype')?></span>
                            </div>
                            <div class="form-group <?=form_error('drivername') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="drivername"><?=$this->lang->line('ambulance_driver_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('drivername') ? 'is-invalid' : '' ?>" id="drivername" name="drivername"  value="<?=set_value('drivername', $ambulance->drivername)?>">
                                <span><?=form_error('drivername')?></span>
                            </div>
                            <div class="form-group <?=form_error('driverlicence') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="driverlicence"><?=$this->lang->line('ambulance_driver_licence')?></label>
                                <input type="text" class="form-control <?=form_error('driverlicence') ? 'is-invalid' : '' ?>" id="driverlicence" name="driverlicence"  value="<?=set_value('driverlicence', $ambulance->driverlicence)?>">
                                <span><?=form_error('driverlicence')?></span>
                            </div>
                            <div class="form-group <?=form_error('drivercontact') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="drivercontact"><?=$this->lang->line('ambulance_driver_contact')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('drivercontact') ? 'is-invalid' : '' ?>" id="drivercontact" name="drivercontact"  value="<?=set_value('drivercontact', $ambulance->drivercontact)?>">
                                <span><?=form_error('drivercontact')?></span>
                            </div>
                            <div class="form-group <?=form_error('type') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="type"><?=$this->lang->line('ambulance_type')?> <span class="text-danger">*</span></label>
                                    <?php
                                    $typeArray['0'] = "— ".$this->lang->line('ambulance_please_select')." —";
                                    $typeArray['1'] = $this->lang->line('ambulance_own');
                                    $typeArray['2'] = $this->lang->line('ambulance_contractual');

                                    $errorClass = form_error('type') ? 'is-invalid' : '';
                                    echo form_dropdown('type', $typeArray,  set_value('type', $ambulance->type), ' class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('type')?></span>
                            </div>
                            <div class="form-group <?=form_error('note') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="note"><?=$this->lang->line('ambulance_note')?></label>
                                <textarea type="text" class="form-control <?=form_error('note') ? 'is-invalid' : '' ?>" id="note" name="note" rows="3"><?=set_value('note', $ambulance->note)?></textarea>
                                <span><?=form_error('note')?></span>
                            </div>
                            <div class="form-group <?=form_error('status') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="status"><?=$this->lang->line('ambulance_status')?> <span class="text-danger">*</span></label>
                                    <?php
                                    $statusArray['0'] = "— ".$this->lang->line('ambulance_please_select')." —";
                                    $statusArray['1'] = $this->lang->line('ambulance_active');
                                    $statusArray['2'] = $this->lang->line('ambulance_inactive');

                                    $errorClass = form_error('status') ? 'is-invalid' : '';
                                    echo form_dropdown('status', $statusArray,  set_value('status', $ambulance->status), ' class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('status')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('ambulance_update')?></button>
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
                        <?php $this->load->view('ambulance/table')?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>