<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-operationtheatrereport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_operationtheatrereport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('operationtheatrereport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('doctorID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="doctorID"><?=$this->lang->line('operationtheatrereport_doctors')?></label>
                            <?php
                            $doctorArray['0'] = '— '.$this->lang->line('operationtheatrereport_please_select').' —';
                            if(inicompute($doctors)) {
                                foreach ($doctors as $doctor) {
                                    $doctorArray[$doctor->userID] = $doctor->name;
                                }
                            }
                            $errorClass = form_error('doctorID') ? 'is-invalid' : '';
                            echo form_dropdown('doctorID', $doctorArray,  set_value('doctorID'), ' id="doctorID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('doctorID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('patientID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="patientID"><?=$this->lang->line('operationtheatrereport_patients')?></label>
                            <?php
                            $patientArray['0'] = '— '.$this->lang->line('operationtheatrereport_please_select').' —';
                            $errorClass = form_error('patientID') ? 'is-invalid' : '';
                            echo form_dropdown('patientID', $patientArray,  set_value('patientID'), ' id="patientID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('patientID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="from_date"><?=$this->lang->line('operationtheatrereport_from_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="to_date"><?=$this->lang->line('operationtheatrereport_to_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_operationtheatrereport" class="btn btn-success get-report-button"> <?=$this->lang->line('operationtheatrereport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_operationtheatrereport"></div>
</article>