<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-beer"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
                <ol class="breadcrumb themebreadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_managesalary')?></li>
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
                            <p class="title"><i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('managesalary_filter')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="form-group <?=form_error('roleID') ? 'text-danger' : '' ?>">
                            <label class="control-label" for="roleID"><?=$this->lang->line('managesalary_role')?><span class="text-danger"> *</span></label>
                                <?php
                                    $roleArray['0'] = $this->lang->line('managesalary_select_role');
                                    if(inicompute($roles)) {
                                        foreach ($roles as $role) {
                                            $roleArray[$role->roleID] = $role->role;
                                        }
                                    }
                                    $errorClass = form_error('roleID') ? 'is-invalid' : '';
                                    echo form_dropdown('roleID', $roleArray,  set_value('roleID', $setroleID), 'class="form-control roleID select2 '.$errorClass.'"')
                                ?>
                            <span><?=form_error('roleID')?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('managesalary_filter_data')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('managesalary/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

