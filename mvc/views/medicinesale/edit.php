<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-medicinesale"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
                <ol class="breadcrumb themebreadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('medicinesale/index/'.$displayID)?>"><?=$this->lang->line('menu_medicinesale')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('medicinesale_edit')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?=($activetab) ? 'active' : ''?>" id="list_tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true"><i class="fa fa-table"></i> <?=$this->lang->line('panel_list')?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=($activetab) ? '' : 'active'?>" id="edit_tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false"><i class="fa fa-edit"></i> <?=$this->lang->line('panel_edit')?></a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade bg-color <?=($activetab) ? 'show active' : ''?>" id="list" role="tabpanel" aria-labelledby="list_tab">
                <?php $this->load->view('medicinesale/table');?>
            </div>
            <div class="tab-pane fade <?=($activetab) ? '' : 'show active'?>" id="edit" role="tabpanel" aria-labelledby="edit_tab">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card card-custom">
                            <div class="card-header">
                                <div class="header-block">
                                    <p class="title"><i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('medicinesale_filter')?></p>
                                </div>
                            </div>
                            <form role="form" method="POST" id="medicinesaleDataForm">
                                <div class="card-block">
                                    <div class="form-group <?=form_error('patient_type') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="patient_type"><?=$this->lang->line('medicinesale_patient_type')?><span class="text-danger"> *</span></label>
                                            <?php
                                            $medicinewarehouseArray['none'] = $this->lang->line("medicinesale_none");
                                            $medicinewarehouseArray['0']    = $this->lang->line("medicinesale_opd");
                                            $medicinewarehouseArray['1']    = $this->lang->line("medicinesale_ipd");
                                            $errorClass = form_error('patient_type') ? 'is-invalid' : '';
                                            echo form_dropdown('patient_type', $medicinewarehouseArray,  set_value('patient_type', $medicinesale->patient_type), ' class="form-control select2 '.$errorClass.'" id="patient_type"')?>
                                        <span><?=form_error('patient_type')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('uhid') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="uhid"><?=$this->lang->line('medicinesale_uhid')?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?=form_error('uhid') ? 'is-invalid' : '' ?>" id="uhid" name="uhid"  value="<?=set_value('uhid', ($medicinesale->uhid!=0) ? $medicinesale->uhid : '' )?>">
                                        <span id="uhid-error"><?=form_error('uhid')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('medicinesaledate') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="medicinesaledate"><?=$this->lang->line('medicinesale_date')?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control datepicker <?=form_error('medicinesaledate') ? 'is-invalid' : '' ?>" id="medicinesaledate" name="medicinesaledate"  value="<?=set_value('medicinesaledate', date('d-m-Y', strtotime($medicinesale->medicinesaledate)))?>">
                                        <span id="medicinesaledate-error"><?=form_error('medicinesaledate')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('payment_status') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="payment_status"><?=$this->lang->line('medicinesale_payment_status')?><span class="text-danger"> *</span></label>
                                            <?php
                                            $paymentstatusArray['none']  = '— '.$this->lang->line("medicinesale_please_select").' —';
                                            $paymentstatusArray['0']  = $this->lang->line('medicinesale_due');
                                            $paymentstatusArray['1']  = $this->lang->line('medicinesale_partial');
                                            $paymentstatusArray['2']  = $this->lang->line('medicinesale_paid');
                                            $errorClass = form_error('payment_status') ? 'is-invalid' : '';
                                            echo form_dropdown('payment_status', $paymentstatusArray,  set_value('payment_status', $medicinesale->medicinesalestatus), ' class="form-control select2 '.$errorClass.'" id="payment_status"')?>
                                        <span id="payment_status-error"><?=form_error('payment_status')?></span>
                                    </div>
                                    <div class="form-group paymentDiv <?=form_error('reference_no') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="reference_no"><?=$this->lang->line('medicinesale_reference_no')?> </label>
                                        <input type="text" class="form-control <?=form_error('reference_no') ? 'is-invalid' : '' ?>" id="reference_no" name="reference_no"  value="<?=set_value('reference_no')?>">
                                        <span><?=form_error('reference_no')?></span>
                                    </div>
                                    <div class="form-group paymentDiv <?=form_error('payment_amount') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="payment_amount"><?=$this->lang->line('medicinesale_amount')?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?=form_error('payment_amount') ? 'is-invalid' : '' ?>" id="payment_amount" name="payment_amount"  value="<?=set_value('payment_amount')?>">
                                        <span id="payment_amount-error"><?=form_error('payment_amount')?></span>
                                    </div>
                                    <div class="form-group paymentDiv <?=form_error('payment_method') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="payment_method"><?=$this->lang->line('medicinesale_payment_method')?><span class="text-danger"> *</span></label>
                                            <?php
                                            $paymentmethodArray['0']  = '— '.$this->lang->line("medicinesale_please_select").' —';
                                            $paymentmethodArray['1']  = $this->lang->line('medicinesale_cash');
                                            $paymentmethodArray['2']  = $this->lang->line('medicinesale_cheque');
                                            $paymentmethodArray['3']  = $this->lang->line('medicinesale_credit_card');
                                            $paymentmethodArray['4']  = $this->lang->line('medicinesale_other');
                                            $errorClass = form_error('payment_method') ? 'is-invalid' : '';
                                            echo form_dropdown('payment_method', $paymentmethodArray,  set_value('payment_method'), ' class="form-control select2 '.$errorClass.'" id="payment_method"')?>
                                        <span id="payment_method-error"><?=form_error('payment_method')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('medicinesalefile') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="medicinesalefile"><?=$this->lang->line('medicinesale_file')?> </label>
                                        <div class="custom-file">
                                            <input type="file" name="medicinesalefile" class="custom-file-input file-upload-input <?=form_error('medicinesalefile') ? 'is-invalid' : '' ?>" id="medicinesalefile">
                                            <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('medicinesale_choose_file')?></label>
                                        </div>
                                        <span><?=form_error('medicinesalefile')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('medicinesaledescription') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="medicinesaledescription"><?=$this->lang->line('medicinesale_description')?></label>
                                        <textarea class="form-control <?=form_error('medicinesaledescription') ? 'is-invalid' : '' ?>" id="medicinesaledescription" name="medicinesaledescription" rows="3" ><?=set_value('medicinesaledescription', $medicinesale->medicinesaledescription)?></textarea>
                                        <span><?=form_error('medicinesaledescription')?></span>
                                    </div>
                                </div>
                                <div class="card-footer"> 
                                    <button type="submit" class="btn btn-primary" id="addMedicineButton"><?=$this->lang->line('medicinesale_update')?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="card card-custom">
                            <div class="card-header">
                                <div class="header-block">
                                    <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('medicinesale_filter_data')?></p>
                                </div>
                            </div>
                            <div class="card-block">
                                <?php $this->load->view('medicinesale/itemedit');?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>           

