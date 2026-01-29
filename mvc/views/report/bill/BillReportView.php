<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-billreport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_billreport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('billreport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('billcategoryID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="billcategoryID"><?=$this->lang->line('billreport_category')?></label>
                            <?php
                            $billcategoryArray[0] = '— '.$this->lang->line('billreport_please_select').' —';
                            if(inicompute($billcategorys)) {
                                foreach($billcategorys as $billcategory) {
                                    $billcategoryArray[$billcategory->billcategoryID] = $billcategory->name;
                                }
                            }
                            $errorClass = form_error('billcategoryID') ? 'is-invalid' : '';
                            echo form_dropdown('billcategoryID', $billcategoryArray,  set_value('billcategoryID'), ' id="billcategoryID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('billcategoryID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('billlabelID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="billlabelID"><?=$this->lang->line('billreport_label')?></label>
                            <?php
                            $billArray[0] = '— '.$this->lang->line('billreport_please_select').' —';
                            $errorClass = form_error('billlabelID') ? 'is-invalid' : '';
                            echo form_dropdown('billlabelID', $billArray,  set_value('billlabelID'), ' id="billlabelID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('billlabelID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('uhid') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="uhid"><?=$this->lang->line('billreport_uhid')?></label>
                            <?php
                            $patientArray['0']  = '— '.$this->lang->line('billreport_please_select').' —';
                            if(inicompute($patients)) {
                                foreach ($patients as $patient) {
                                    $patientArray[$patient->patientID]  = $patient->patientID.' - '.$patient->name;
                                }
                            }
                            $errorClass = form_error('uhid') ? 'is-invalid' : '';
                            echo form_dropdown('uhid', $patientArray,  set_value('uhid'), ' id="uhid" class="form-control select2 '.$errorClass.'" ')?>
                        <span id="uhid-error"><?=form_error('uhid')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('paymentstatus') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="paymentstatus"><?=$this->lang->line('billreport_payment_status')?></label>
                            <?php
                            $paymentstatusArray['0']  = '— '.$this->lang->line('billreport_please_select').' —';
                            $paymentstatusArray['1']  = $this->lang->line('billreport_due');
                            $paymentstatusArray['2']  = $this->lang->line('billreport_paid');

                            $errorClass = form_error('paymentstatus') ? 'is-invalid' : '';
                            echo form_dropdown('paymentstatus', $paymentstatusArray,  set_value('paymentstatus'), ' id="paymentstatus" class="form-control select2 '.$errorClass.'" ')?>
                        <span id="paymentstatus-error"><?=form_error('paymentstatus')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="from_date"><?=$this->lang->line('billreport_from_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="to_date"><?=$this->lang->line('billreport_to_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_billreport" class="btn btn-success get-report-button"> <?=$this->lang->line('billreport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_billreport"></div>
</article>