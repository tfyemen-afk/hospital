<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-tpareport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_tpareport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('tpareport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('tpaID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="tpaID"><?=$this->lang->line('tpareport_tpa')?></label>
                            <?php
                            $tpaArray[0] = '— '.$this->lang->line('tpareport_please_select').' —';
                            if(inicompute($tpas)) {
                                foreach($tpas as $tpa) {
                                    $tpaArray[$tpa->tpaID] = $tpa->name;
                                }
                            }
                            $errorClass = form_error('tpaID') ? 'is-invalid' : '';
                            echo form_dropdown('tpaID', $tpaArray,  set_value('tpaID'), ' id="tpaID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('tpaID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('typeID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="typeID"><?=$this->lang->line('tpareport_type')?></label>
                            <?php
                            $typeArray[0] = '— '.$this->lang->line('tpareport_please_select').' —';
                            $typeArray[1] = $this->lang->line('tpareport_admission');
                            $typeArray[2] = $this->lang->line('tpareport_appointment');
                            $errorClass = form_error('typeID') ? 'is-invalid' : '';
                            echo form_dropdown('typeID', $typeArray,  set_value('typeID'), ' id="typeID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('typeID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="from_date"><?=$this->lang->line('tpareport_from_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="to_date"><?=$this->lang->line('tpareport_to_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_tpareport" class="btn btn-success get-report-button"> <?=$this->lang->line('tpareport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_tpareport"></div>
</article>