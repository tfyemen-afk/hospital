<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-salaryreport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_salaryreport')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('salaryreport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('roleID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="roleID"><?=$this->lang->line('salaryreport_role')?></label>
                         <?php
                            $roleArray['0'] = $this->lang->line('salaryreport_please_select');
                            if(inicompute($roles)) {
                                foreach ($roles as $role) {
                                    $roleArray[$role->roleID] = $role->role;
                                }
                            }
                            $errorClass = form_error('roleID') ? 'is-invalid' : '';
                            echo form_dropdown('roleID', $roleArray,  set_value('roleID'), ' id="roleID" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('roleID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('userID') ? 'text-danger' : '' ?>" id="userDiv">
                        <label class="control-label" for="userID"><?=$this->lang->line('salaryreport_employee')?></label>
                         <?php
                            $userArray['0'] = $this->lang->line('salaryreport_please_select');
                            $errorClass = form_error('userID') ? 'is-invalid' : '';
                            echo form_dropdown('userID', $userArray,  set_value('userID'), ' id="userID" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('userID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('month') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="month"><?=$this->lang->line('salaryreport_month')?></label>
                        <input type="text" class="form-control <?=form_error('month') ? 'is-invalid' : '' ?>" id="month" name="month"  value="<?=set_value('month')?>">
                        <span><?=form_error('month')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="from_date"><?=$this->lang->line('salaryreport_from_date')?></label>
                        <input type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="to_date"><?=$this->lang->line('salaryreport_to_date')?></label>
                        <input type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_salaryreport" class="btn btn-success get-report-button"> <?=$this->lang->line('salaryreport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_salaryreport"></div>
</article>