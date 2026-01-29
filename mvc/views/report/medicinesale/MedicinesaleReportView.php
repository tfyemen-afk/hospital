<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-medicinesalereport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_medicinesalereport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('medicinesalereport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('patient_type') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="patient_type"><?=$this->lang->line('medicinesalereport_patient_type')?></label>
                         <?php
                            $patient_typeArray['0'] = $this->lang->line('medicinesalereport_please_select');
                            $patient_typeArray['3'] = $this->lang->line('medicinesalereport_none');
                            $patient_typeArray['1'] = $this->lang->line('medicinesalereport_opd');
                            $patient_typeArray['2'] = $this->lang->line('medicinesalereport_ipd');
                            $errorClass = form_error('patient_type') ? 'is-invalid' : '';
                            echo form_dropdown('patient_type', $patient_typeArray,  set_value('patient_type'), ' id="patient_type" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('patient_type')?></span>
                    </div>
                    <div class="form-group col-md-4 uhidDIV <?=form_error('uhid') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="uhid"><?=$this->lang->line('medicinesalereport_uhid')?></label>
                        <input type="text" class="form-control <?=form_error('uhid') ? 'is-invalid' : '' ?> " id="uhid" name="uhid"  value="<?=set_value('uhid')?>">
                        <span><?=form_error('uhid')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('statusID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="statusID"><?=$this->lang->line('medicinesalereport_status')?></label>
                         <?php
                            $statusArray['0'] = $this->lang->line('medicinesalereport_please_select');
                            $statusArray['1'] = $this->lang->line('medicinesalereport_pending');
                            $statusArray['2'] = $this->lang->line('medicinesalereport_partial');
                            $statusArray['3'] = $this->lang->line('medicinesalereport_fully_paid');
                            $statusArray['4'] = $this->lang->line('medicinesalereport_refund');
                            $errorClass = form_error('statusID') ? 'is-invalid' : '';
                            echo form_dropdown('statusID', $statusArray,  set_value('statusID'), ' id="statusID" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('statusID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="from_date"><?=$this->lang->line('medicinesalereport_from_date')?></label>
                        <input type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="to_date"><?=$this->lang->line('medicinesalereport_to_date')?></label>
                        <input type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_medicinesalereport" class="btn btn-success get-report-button"> <?=$this->lang->line('medicinesalereport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_medicinesalereport"></div>
</article>