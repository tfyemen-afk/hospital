<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-dischargereport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_dischargereport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('dischargereport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('conditionofdischarge') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="conditionofdischarge"><?=$this->lang->line('dischargereport_condition_of_discharge')?></label>
                            <?php
                            $conditionofdischargeArray[0] = '— '.$this->lang->line('dischargereport_please_select').' —';
                            $conditionofdischargeArray[1] = $this->lang->line('dischargereport_stable');
                            $conditionofdischargeArray[2] = $this->lang->line('dischargereport_almost_stable');
                            $conditionofdischargeArray[3] = $this->lang->line('dischargereport_own_risk');
                            $conditionofdischargeArray[4] = $this->lang->line('dischargereport_unstable');
                            $errorClass = form_error('conditionofdischarge') ? 'is-invalid' : '';
                            echo form_dropdown('conditionofdischarge', $conditionofdischargeArray,  set_value('conditionofdischarge'), ' id="conditionofdischarge" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('conditionofdischarge')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="from_date"><?=$this->lang->line('dischargereport_from_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="to_date"><?=$this->lang->line('dischargereport_to_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_dischargereport" class="btn btn-success get-report-button"> <?=$this->lang->line('dischargereport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_dischargereport"></div>
</article>