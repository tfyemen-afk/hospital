<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-leaveapplicationreport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_leaveapplicationreport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('leaveapplicationreport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('roleID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="roleID"><?=$this->lang->line('leaveapplicationreport_role')?></label>
                         <?php
                            $roleArray['0'] = $this->lang->line('leaveapplicationreport_please_select');
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
                        <label class="control-label" for="userID"><?=$this->lang->line('leaveapplicationreport_employee')?></label>
                         <?php
                            $userArray['0'] = $this->lang->line('leaveapplicationreport_please_select');
                            $errorClass = form_error('userID') ? 'is-invalid' : '';
                            echo form_dropdown('userID', $userArray,  set_value('userID'), ' id="userID" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('userID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('categoryID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="categoryID"><?=$this->lang->line('leaveapplicationreport_category')?></label>
                         <?php
                            $categoryArray['0'] = $this->lang->line('leaveapplicationreport_please_select');
                            if(inicompute($leavecategorys)) {
                                foreach ($leavecategorys as $leavecategory) {
                                    $categoryArray[$leavecategory->leavecategoryID] = $leavecategory->leavecategory;
                                }
                            }
                            $errorClass = form_error('categoryID') ? 'is-invalid' : '';
                            echo form_dropdown('categoryID', $categoryArray,  set_value('categoryID'), ' id="categoryID" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('categoryID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('statusID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="statusID"><?=$this->lang->line('leaveapplicationreport_status')?></label>
                         <?php
                            $statusArray['0'] = $this->lang->line('leaveapplicationreport_please_select');
                            $statusArray['1'] = $this->lang->line('leaveapplicationreport_pending');
                            $statusArray['2'] = $this->lang->line('leaveapplicationreport_declined');
                            $statusArray['3'] = $this->lang->line('leaveapplicationreport_approved');
                            $errorClass       = form_error('statusID') ? 'is-invalid' : '';
                            echo form_dropdown('statusID', $statusArray,  set_value('statusID'), ' id="statusID" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('statusID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="name"><?=$this->lang->line('leaveapplicationreport_from_date')?></label>
                        <input type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="name"><?=$this->lang->line('leaveapplicationreport_to_date')?></label>
                        <input type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_leaveapplicationreport" class="btn btn-success get-report-button"> <?=$this->lang->line('leaveapplicationreport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_leaveapplicationreport"></div>
</article>