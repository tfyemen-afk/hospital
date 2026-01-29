<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-attendancereport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_attendancereport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('attendancereport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('attendancetype') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="attendancetype"><?=$this->lang->line('attendancereport_attendance_type')?><span class="text-danger"> *</span></label>
                         <?php
                            $attendancetypeArray['0'] = "— ".$this->lang->line('attendancereport_please_select')." —";
                            $attendancetypeArray['P'] = $this->lang->line('attendancereport_present');
                            $attendancetypeArray['LE'] = $this->lang->line('attendancereport_late_present_with_excuse');
                            $attendancetypeArray['L'] = $this->lang->line('attendancereport_late_present');
                            $attendancetypeArray['A'] = $this->lang->line('attendancereport_absent');
                            
                            $errorClass = form_error('attendancetype') ? 'is-invalid' : '';
                            echo form_dropdown('attendancetype', $attendancetypeArray,  set_value('attendancetype'), ' class="form-control select2 '.$errorClass.'" id="attendancetype"'); ?>
                        <span><?=form_error('attendancetype')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="date"><?=$this->lang->line('attendancereport_date')?><span class="text-danger"> *</span></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('date') ? 'is-invalid' : '' ?> datepicker" id="date" name="date"  value="<?=set_value('date')?>">
                        <span><?=form_error('date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_attendancereport" class="btn btn-success get-report-button"> <?=$this->lang->line('attendancereport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="load_attendancereport"></div>
</article>