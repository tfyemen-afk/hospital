<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-resetpassword"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_resetpassword')?></li>
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
                                <p class="title"> <i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_reset')?></p>
                            </div>
                        </div>
                        <form role="form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <div class="form-group <?=form_error('roleID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="roleID"><?=$this->lang->line('resetpassword_role')?><span class="text-danger"> *</span></label>
                                     <?php
                                        $roleArray['0'] = $this->lang->line('resetpassword_please_select');
                                        if(inicompute($roles)) {
                                            foreach ($roles as $role) {
                                                $roleArray[$role->roleID] = $role->role;
                                            }
                                        }
                                        $errorClass = form_error('roleID') ? 'is-invalid' : '';
                                        echo form_dropdown('roleID', $roleArray,  set_value('roleID'), ' id="roleID" class="form-control select2 '.$errorClass.'"'); ?>
                                    <span><?=form_error('roleID')?></span>
                                </div>
                                <div class="form-group <?=form_error('userID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="userID"><?=$this->lang->line('resetpassword_username')?><span class="text-danger"> *</span></label>
                                     <?php
                                        $userArray['0'] = $this->lang->line('resetpassword_please_select');
                                        if(inicompute($roleusers)) {
                                            foreach ($roleusers as $user) {
                                                $userArray[$user->userID] = $user->username;
                                            }
                                        }
                                        $errorClass = form_error('userID') ? 'is-invalid' : '';
                                        echo form_dropdown('userID', $userArray,  set_value('userID'), ' id="userID" class="form-control select2 '.$errorClass.'"'); ?>
                                    <span><?=form_error('userID')?></span>
                                </div>
                                <div class="form-group <?=form_error('newpassword') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="newpassword"><?=$this->lang->line('resetpassword_newpassword')?><span class="text-danger"> *</span></label>
                                    <input type="password" class="form-control <?=form_error('newpassword') ? 'is-invalid' : '' ?>" id="newpassword" name="newpassword"  value="<?=set_value('newpassword')?>">
                                    <span><?=form_error('newpassword')?></span>
                                </div>
                                <div class="form-group <?=form_error('repassword') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="repassword"><?=$this->lang->line('resetpassword_repassword')?><span class="text-danger"> *</span></label>
                                    <input type="password" class="form-control <?=form_error('repassword') ? 'is-invalid' : '' ?>" id="repassword" name="repassword"  value="<?=set_value('repassword')?>">
                                    <span><?=form_error('repassword')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('resetpassword_reset')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('resetpassword/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>