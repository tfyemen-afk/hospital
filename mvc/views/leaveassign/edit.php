<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-leaveassign"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
              <ol class="breadcrumb themebreadcrumb pull-right">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('leaveassign/index')?>"><?=$this->lang->line('menu_leaveassign')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('leaveassign_edit')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-edit"></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('roleID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="roleID"><?=$this->lang->line('leaveassign_role')?> <span class="text-danger">*</span></label>
                                <?php
                                    $roleArray['0'] = $this->lang->line('leaveassign_please_select');
                                    if(inicompute($roles)) {
                                        foreach ($roles as $role) {
                                            $roleArray[$role->roleID] = $role->role;
                                        }
                                    }
                                    $errorClass = form_error('roleID') ? 'is-invalid' : '';
                                    echo form_dropdown('roleID', $roleArray,  set_value('roleID', $leaveassign->roleID), ' class="form-control select2 '.$errorClass.'" ');
                                ?>
                                <span><?=form_error('roleID')?></span>
                            </div>
                            <div class="form-group <?=form_error('leavecategoryID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="leavecategoryID"><?=$this->lang->line('leaveassign_category')?> <span class="text-danger">*</span></label>
                                <?php
                                    $leavecategoryArray['0'] = $this->lang->line('leaveassign_please_select');
                                    if(inicompute($leavecategorys)) {
                                        foreach ($leavecategorys as $leavecategory) {
                                            $leavecategoryArray[$leavecategory->leavecategoryID] = $leavecategory->leavecategory;
                                        }
                                    }

                                    $errorClass = form_error('leavecategoryID') ? 'is-invalid' : '';
                                    echo form_dropdown('leavecategoryID', $leavecategoryArray,  set_value('leavecategoryID', $leaveassign->leavecategoryID), ' class="form-control select2 '.$errorClass.'" ');
                                ?>
                                <span><?=form_error('leavecategoryID')?></span>
                            </div>
                            <div class="form-group <?=form_error('year') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="year"><?=$this->lang->line('leaveassign_year')?> <span class="text-danger">*</span></label>
                                <?php
                                    $yearArray['0'] = $this->lang->line('leaveassign_please_select');
                                    if(inicompute($yearlists)) {
                                        foreach ($yearlists as $yearlist) {
                                            $yearArray[$yearlist] = $yearlist;
                                        }
                                    }
                                    $errorClass = form_error('year') ? 'is-invalid' : '';
                                    echo form_dropdown('year', $yearArray,  set_value('year', $leaveassign->year), ' class="form-control select2 '.$errorClass.'" ');
                                ?>
                                <span><?=form_error('year')?></span>
                            </div>
                            <div class="form-group <?=form_error('leaveassignday') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="leaveassignday"><?=$this->lang->line('leaveassign_no_of_day')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('leaveassignday') ? 'is-invalid' : '' ?>" id="leaveassignday" name="leaveassignday"  value="<?=set_value('leaveassignday', $leaveassign->leaveassignday)?>">
                                <span><?=form_error('leaveassignday')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('leaveassign_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('leaveassign/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>