<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-ambulancecall"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('ambulancecall/index/'.$displayID)?>"><?=$this->lang->line('menu_ambulancecall')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('ambulancecall_edit')?></li>
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
                            <div class="form-group <?=form_error('ambulanceID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="ambulanceID"><?=$this->lang->line('ambulancecall_ambulance')?> <span class="text-danger">*</span></label>
                                    <?php
                                    $ambulanceArray['0'] = "— ".$this->lang->line('ambulancecall_please_select')." —";
                                    if(inicompute($ambulances)) {
                                        foreach ($ambulances as $ambulance) {
                                            if ($ambulance->status == 1) {
                                                $ambulanceArray[$ambulance->ambulanceID] = $ambulance->name;
                                            }
                                        }
                                    }
                                    $errorClass = form_error('ambulanceID') ? 'is-invalid' : '';
                                    echo form_dropdown('ambulanceID', $ambulanceArray,  set_value('ambulanceID', $ambulancecall->ambulanceID), ' id="ambulanceID" class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('ambulanceID')?></span>
                            </div>
                            <div class="form-group <?=form_error('drivername') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="drivername"><?=$this->lang->line('ambulancecall_driver_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('drivername') ? 'is-invalid' : '' ?>" id="drivername" name="drivername"  value="<?=set_value('drivername', $ambulancecall->drivername)?>">
                                <span><?=form_error('drivername')?></span>
                            </div>
                            <div class="form-group <?=form_error('date') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="date"><?=$this->lang->line('ambulancecall_date')?> <span class="text-danger">*</span></label>
                                <input autocomplete="off" type="text" class="form-control datepicker <?=form_error('date') ? 'is-invalid' : '' ?>" id="date" name="date"  value="<?=set_value('date', date('d-m-Y h:i A', strtotime($ambulancecall->date)))?>">
                                <span><?=form_error('date')?></span>
                            </div>
                            <div class="form-group <?=form_error('amount') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="amount"><?=$this->lang->line('ambulancecall_amount')?></label>
                                <input type="text" class="form-control <?=form_error('amount') ? 'is-invalid' : '' ?>" id="amount" name="amount"  value="<?=set_value('amount', $ambulancecall->amount)?>">
                                <span><?=form_error('amount')?></span>
                            </div>
                            <div class="form-group <?=form_error('patientname') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="patientname"><?=$this->lang->line('ambulancecall_patient_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('patientname') ? 'is-invalid' : '' ?>" id="patientname" name="patientname"  value="<?=set_value('patientname', $ambulancecall->patientname)?>">
                                <span><?=form_error('patientname')?></span>
                            </div>
                            <div class="form-group <?=form_error('patientcontact') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="patientcontact"><?=$this->lang->line('ambulancecall_patient_contact')?></label>
                                <input type="text" class="form-control <?=form_error('patientcontact') ? 'is-invalid' : '' ?>" id="patientcontact" name="patientcontact"  value="<?=set_value('patientcontact', $ambulancecall->patientcontact)?>">
                                <span><?=form_error('patientcontact')?></span>
                            </div>
                            <div class="form-group <?=form_error('pickup_point') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="pickup_point"><?=$this->lang->line('ambulancecall_pickup_point')?></label>
                                <textarea type="text" class="form-control <?=form_error('pickup_point') ? 'is-invalid' : '' ?>" id="pickup_point" name="pickup_point" rows="3"><?=set_value('pickup_point', $ambulancecall->pickup_point)?></textarea>
                                <span><?=form_error('pickup_point')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('ambulancecall_update')?></button>
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
                        <?php $this->load->view('ambulancecall/table')?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>