<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-bedreport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_bedreport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('bedreport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('wardID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="wardID"><?=$this->lang->line('bedreport_ward')?></label>
                        <?php
                            $wardArray[0] = '— '.$this->lang->line('bedreport_please_select').' —';
                            if(inicompute($wards)) {
                                foreach($wards as $ward) {
                                    $wardArray[$ward->wardID] = $ward->name." - ". (isset($rooms[$ward->wardID]) ? $rooms[$ward->wardID] : '');
                                }
                            }
                            $errorClass = form_error('wardID') ? 'is-invalid' : '';
                            echo form_dropdown('wardID', $wardArray,  set_value('wardID'), ' id="wardID" class="form-control select2 '.$errorClass.'"');
                        ?>
                        <span><?=form_error('wardID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('bedtypeID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="bedtypeID"><?=$this->lang->line('bedreport_bed_type')?></label>
                            <?php
                            $bedtypeArray[0] = '— '.$this->lang->line('bedreport_please_select').' —';
                            if(inicompute($bedtypes)) {
                                foreach($bedtypes as $bedtype) {
                                    $bedtypeArray[$bedtype->bedtypeID] = $bedtype->name;
                                }
                            }
                            $errorClass = form_error('bedtypeID') ? 'is-invalid' : '';
                            echo form_dropdown('bedtypeID', $bedtypeArray,  set_value('bedtypeID'), ' id="bedtypeID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('bedtypeID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('bedID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="bedID"><?=$this->lang->line('bedreport_bed')?></label>
                            <?php
                            $bedArray[0] = '— '.$this->lang->line('bedreport_please_select').' —';
                            $errorClass = form_error('bedID') ? 'is-invalid' : '';
                            echo form_dropdown('bedID', $bedArray,  set_value('bedID'), ' id="bedID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('bedID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('statusID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="statusID"><?=$this->lang->line('bedreport_status')?></label>
                            <?php
                            $statusArray[0] = '— '.$this->lang->line('bedreport_please_select').' —';
                            $statusArray[1] = $this->lang->line('bedreport_avialable');
                            $statusArray[2] = $this->lang->line('bedreport_unavailable');
                            $errorClass = form_error('statusID') ? 'is-invalid' : '';
                            echo form_dropdown('statusID', $statusArray,  set_value('statusID'), ' id="statusID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('statusID')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_bedreport" class="btn btn-success get-report-button"> <?=$this->lang->line('bedreport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_bedreport"></div>
</article>

