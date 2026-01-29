<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-medicinepurchase"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
                <ol class="breadcrumb themebreadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_medicinepurchase')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?=($activetab) ? 'active' : ''?>" id="list_tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true"><i class="fa fa-table"></i> <?=$this->lang->line('panel_list')?></a>
            </li>
            <?php if(permissionChecker('medicinepurchase_add')) { ?>
                <li class="nav-item">
                    <a class="nav-link <?=($activetab) ? '' : 'active'?>" id="add_tab" data-toggle="tab" href="#add" role="tab" aria-controls="add" aria-selected="false"><i class="fa fa-plus-square-o"></i> <?=$this->lang->line('panel_add')?></a>
                </li>
            <?php } ?>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade bg-color <?=($activetab) ? 'show active' : ''?>" id="list" role="tabpanel" aria-labelledby="list_tab">
                <?php $this->load->view('medicinepurchase/table');?>
            </div>
            <?php if(permissionChecker('medicinepurchase_add')) { ?>
                <div class="tab-pane fade <?=($activetab) ? '' : 'show active'?>" id="add" role="tabpanel" aria-labelledby="add_tab">
                    <div class="row">
                        <?php if(permissionChecker('medicinepurchase_add')) { ?>
                            <div class="col-sm-3">
                                <div class="card card-custom">
                                    <div class="card-header">
                                        <div class="header-block">
                                            <p class="title"><i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('medicinepurchase_filter')?></p>
                                        </div>
                                    </div>
                                    <form role="form" method="POST" id="medicinePurchaseDataForm">
                                        <div class="card-block">
                                            <div class="form-group <?=form_error('medicinewarehouseID') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="medicinewarehouseID"><?=$this->lang->line('medicinepurchase_warehouse')?> <span class="text-danger">*</span></label>
                                                    <?php
                                                    $medicinewarehouseArray['0'] = '— '.$this->lang->line("medicinepurchase_please_select").' —';
                                                    if(inicompute($medicinewarehouses)) {
                                                        foreach ($medicinewarehouses as $medicinewarehouse) {
                                                            $medicinewarehouseArray[$medicinewarehouse->medicinewarehouseID] = $medicinewarehouse->name;
                                                        }
                                                    }
                                                    $errorClass = form_error('medicinewarehouseID') ? 'is-invalid' : '';
                                                    echo form_dropdown('medicinewarehouseID', $medicinewarehouseArray,  set_value('medicinewarehouseID'), ' class="form-control select2 '.$errorClass.'" id="medicinewarehouseID"')?>
                                                <span id="medicinewarehouseID-error"><?=form_error('medicinewarehouseID')?></span>
                                            </div>

                                            <div class="form-group <?=form_error('purchase_referenceno') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="purchase_referenceno"> <?=$this->lang->line('medicinepurchase_reference_no')?> <span class="text-danger">*</span> </label>
                                                <input type="text" class="form-control <?=form_error('purchase_referenceno') ? 'is-invalid' : '' ?>" id="purchase_referenceno" name="purchase_referenceno"  value="<?=set_value('purchase_referenceno')?>">
                                                <span id="purchase_referenceno-error"><?=form_error('purchase_referenceno')?></span>
                                            </div>

                                            <div class="form-group <?=form_error('medicinepurchasedate') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="medicinepurchasedate"><?=$this->lang->line('medicinepurchase_date')?> <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control datepicker <?=form_error('medicinepurchasedate') ? 'is-invalid' : '' ?>" id="medicinepurchasedate" name="medicinepurchasedate"  value="<?=set_value('medicinepurchasedate')?>">
                                                <span id="medicinepurchasedate-error"><?=form_error('medicinepurchasedate')?></span>
                                            </div>
                                            <div class="form-group <?=form_error('payment_status') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="payment_status"><?=$this->lang->line('medicinepurchase_payment_status')?><span class="text-danger"> *</span></label>
                                                    <?php
                                                    $paymentstatusArray['none']  = '— '.$this->lang->line("medicinepurchase_please_select").' —';
                                                    $paymentstatusArray['0']  = $this->lang->line('medicinepurchase_due');
                                                    $paymentstatusArray['1']  = $this->lang->line('medicinepurchase_partial');
                                                    $paymentstatusArray['2']  = $this->lang->line('medicinepurchase_paid');
                                                    $errorClass = form_error('payment_status') ? 'is-invalid' : '';
                                                    echo form_dropdown('payment_status', $paymentstatusArray,  set_value('payment_status'), ' class="form-control select2 '.$errorClass.'" id="payment_status"')?>
                                                <span id="payment_status-error"><?=form_error('payment_status')?></span>
                                            </div>
                                            <div class="form-group paymentDiv <?=form_error('purchasepaid_referenceno') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="purchasepaid_referenceno"><?=$this->lang->line('medicinepurchase_reference_no')?> </label>
                                                <input type="text" class="form-control <?=form_error('purchasepaid_referenceno') ? 'is-invalid' : '' ?>" id="purchasepaid_referenceno" name="purchasepaid_referenceno"  value="<?=set_value('purchasepaid_referenceno')?>">
                                                <span id="purchasepaid_referenceno-error"><?=form_error('purchasepaid_referenceno')?></span>
                                            </div>
                                            <div class="form-group paymentDiv <?=form_error('payment_amount') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="payment_amount"><?=$this->lang->line('medicinepurchase_amount')?> <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control <?=form_error('payment_amount') ? 'is-invalid' : '' ?>" id="payment_amount" name="payment_amount"  value="<?=set_value('payment_amount')?>">
                                                <span id="payment_amount-error"><?=form_error('payment_amount')?></span>
                                            </div>
                                            <div class="form-group paymentDiv <?=form_error('purchasepaid_payment_method') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="purchasepaid_payment_method"><?=$this->lang->line('medicinepurchase_payment_method')?><span class="text-danger"> *</span></label>
                                                    <?php
                                                    $paymentmethodArray['0']  = '— '.$this->lang->line("medicinepurchase_please_select").' —';
                                                    $paymentmethodArray['1']  = $this->lang->line('medicinepurchase_cash');
                                                    $paymentmethodArray['2']  = $this->lang->line('medicinepurchase_cheque');
                                                    $paymentmethodArray['3']  = $this->lang->line('medicinepurchase_credit_card');
                                                    $paymentmethodArray['4']  = $this->lang->line('medicinepurchase_other');
                                                    $errorClass = form_error('purchasepaid_payment_method') ? 'is-invalid' : '';
                                                    echo form_dropdown('purchasepaid_payment_method', $paymentmethodArray,  set_value('purchasepaid_payment_method'), ' class="form-control select2 '.$errorClass.'" id="purchasepaid_payment_method"')?>
                                                <span id="purchasepaid_payment_method-error"><?=form_error('purchasepaid_payment_method')?></span>
                                            </div>
                                            <div class="form-group <?=form_error('medicinepurchasefile') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="medicinepurchasefile"><?=$this->lang->line('medicinepurchase_file')?> </label>
                                                <div class="custom-file">
                                                    <input type="file" name="medicinepurchasefile" class="custom-file-input file-upload-input <?=form_error('medicinepurchasefile') ? 'is-invalid' : '' ?>" id="medicinepurchasefile">
                                                    <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('medicinepurchase_choose_file')?></label>
                                                </div>
                                                <span><?=form_error('medicinepurchasefile')?></span>
                                            </div>
                                            <div class="form-group <?=form_error('medicinepurchasedescription') ? 'text-danger' : '' ?>">
                                                <label class="control-label" for="medicinepurchasedescription"><?=$this->lang->line('medicinepurchase_description')?></label>
                                                <textarea class="form-control <?=form_error('medicinepurchasedescription') ? 'is-invalid' : '' ?>" id="medicinepurchasedescription" name="medicinepurchasedescription" rows="3" ><?=set_value('medicinepurchasedescription')?></textarea>
                                                <span><?=form_error('medicinepurchasedescription')?></span>
                                            </div>
                                        </div>
                                        <div class="card-footer"> 
                                            <button type="submit" class="btn btn-primary" id="addMedicineButton"><?=$this->lang->line('medicinepurchase_add')?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="<?=(permissionChecker('medicinepurchase_add') ? 'col-sm-9' : 'col-sm-12')?>">
                            <div class="card card-custom">
                                <div class="card-header">
                                    <div class="header-block">
                                        <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('medicinepurchase_filter_data')?></p>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <?php $this->load->view('medicinepurchase/itemadd');?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</article>
