<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-idcardreport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_idcardreport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('idcardreport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('roleID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="roleID"><?=$this->lang->line('idcardreport_role')?><span class="text-danger"> *</span></label>
                         <?php
                            $roleArray['0'] = $this->lang->line('idcardreport_please_select');
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
                        <label class="control-label" for="userID"><?=$this->lang->line('idcardreport_user')?></label>
                         <?php
                            $userArray['0'] = $this->lang->line('idcardreport_please_select');
                            $errorClass = form_error('userID') ? 'is-invalid' : '';
                            echo form_dropdown('userID', $userArray,  set_value('userID'), ' id="userID" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('userID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('type') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="type"><?=$this->lang->line('idcardreport_type')?><span class="text-danger"> *</span></label>
                         <?php
                            $typeArray['0'] = $this->lang->line('idcardreport_please_select');
                            $typeArray['1'] = $this->lang->line('idcardreport_front_part');
                            $typeArray['2'] = $this->lang->line('idcardreport_back_part');
                            $errorClass       = form_error('type') ? 'is-invalid' : '';
                            echo form_dropdown('type', $typeArray,  set_value('type'), ' id="type" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('type')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('background') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="background"><?=$this->lang->line('idcardreport_background')?><span class="text-danger"> *</span></label>
                         <?php
                            $backgroundArray['0'] = $this->lang->line('idcardreport_please_select');
                            $backgroundArray['1'] = $this->lang->line('idcardreport_yes');
                            $backgroundArray['2'] = $this->lang->line('idcardreport_no');
                            $errorClass       = form_error('background') ? 'is-invalid' : '';
                            echo form_dropdown('background', $backgroundArray,  set_value('background'), ' id="background" class="form-control select2 '.$errorClass.'"'); ?>
                        <span><?=form_error('background')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_idcardreport" class="btn btn-success get-report-button"> <?=$this->lang->line('idcardreport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_idcardreport"></div>
</article>