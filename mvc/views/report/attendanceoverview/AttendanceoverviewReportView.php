<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-attendanceoverviewreport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_attendanceoverviewreport')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('attendanceoverviewreport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('roleID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="roleID"><?=$this->lang->line('attendanceoverviewreport_report_for')?></label>
                         <?php
                            $roleArray['0'] = "— ".$this->lang->line('attendanceoverviewreport_please_select')." —";
                            if(inicompute($roles)) {
                                foreach($roles as $roleKey => $role) {
                                    $roleArray[$roleKey] = $role;
                                }
                            }
                            $errorClass = form_error('roleID') ? 'is-invalid' : '';
                            echo form_dropdown('roleID', $roleArray,  set_value('roleID'), ' class="form-control select2 '.$errorClass.'" id="roleID"'); ?>
                        <span><?=form_error('roleID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('userID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="userID"><?=$this->lang->line('attendanceoverviewreport_user')?></label>
                         <?php
                            $userArray['0'] = "— ".$this->lang->line('attendanceoverviewreport_please_select')." —";
                            
                            $errorClass = form_error('userID') ? 'is-invalid' : '';
                            echo form_dropdown('userID', $userArray,  set_value('userID'), ' class="form-control select2 '.$errorClass.'" id="userID"'); ?>
                        <span><?=form_error('userID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('month') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="month"><?=$this->lang->line('attendanceoverviewreport_month')?><span class="text-danger"> *</span></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('month') ? 'is-invalid' : '' ?> monthpicker" id="month" name="month"  value="<?=set_value('month')?>">
                        <span><?=form_error('month')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_attendanceoverviewreport" class="btn btn-success get-report-button"> <?=$this->lang->line('attendanceoverviewreport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_attendanceoverviewreport"></div>
</article>