<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-medicinepurchasereport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_medicinepurchasereport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('medicinepurchasereport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('medicinewarehouseID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="medicinewarehouseID"><?=$this->lang->line('medicinepurchasereport_warehouse')?></label>
                         <?php
                            $warehouseArray['0'] = $this->lang->line('medicinepurchasereport_please_select');
                            if(inicompute($medicinewarehouses)) {
                                foreach ($medicinewarehouses as $medicinewarehouse) {
                                    $warehouseArray[$medicinewarehouse->medicinewarehouseID] = $medicinewarehouse->name;
                                }
                            }
                            $errorClass = form_error('medicinewarehouseID') ? 'is-invalid' : '';
                            echo form_dropdown('medicinewarehouseID', $warehouseArray,  set_value('medicinewarehouseID'), ' id="medicinewarehouseID" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('medicinewarehouseID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('reference_no') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="reference_no"><?=$this->lang->line('medicinepurchasereport_reference_no')?></label>
                        <input type="text" class="form-control <?=form_error('reference_no') ? 'is-invalid' : '' ?>" id="reference_no" name="reference_no"  value="<?=set_value('reference_no')?>">
                        <span><?=form_error('reference_no')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('statusID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="statusID"><?=$this->lang->line('medicinepurchasereport_status')?></label>
                         <?php
                            $statusArray['0'] = $this->lang->line('medicinepurchasereport_please_select');
                            $statusArray['1'] = $this->lang->line('medicinepurchasereport_pending');
                            $statusArray['2'] = $this->lang->line('medicinepurchasereport_partial');
                            $statusArray['3'] = $this->lang->line('medicinepurchasereport_fully_paid');
                            $statusArray['4'] = $this->lang->line('medicinepurchasereport_refund');
                            $errorClass = form_error('statusID') ? 'is-invalid' : '';
                            echo form_dropdown('statusID', $statusArray,  set_value('statusID'), ' id="statusID" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('statusID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="from_date"><?=$this->lang->line('medicinepurchasereport_from_date')?></label>
                        <input type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="to_date"><?=$this->lang->line('medicinepurchasereport_to_date')?></label>
                        <input type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_medicinepurchasereport" class="btn btn-success get-report-button"> <?=$this->lang->line('medicinepurchasereport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_medicinepurchasereport"></div>
</article>