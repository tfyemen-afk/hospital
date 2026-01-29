<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-blooddonor"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_bloodstock')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('bloodstock_add')) { ?>
                <div class="col-sm-4">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="header-block">
                                <p class="title"><i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                            </div>
                        </div>
                        <form role="form" method="POST">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <div class="form-group <?=form_error('bloodgroupID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="bloodgroupID"><?=$this->lang->line('bloodstock_blood_group')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $bloodgroupArray['0'] = '— '.$this->lang->line('bloodstock_please_select').' —';
                                        if(inicompute($bloodgroups)) {
                                            foreach ($bloodgroups as $bloodgroup) {
                                                $bloodgroupArray[$bloodgroup->bloodgroupID] = $bloodgroup->bloodgroup;
                                            }
                                        }
                                        $errorClass = form_error('bloodgroupID') ? 'is-invalid' : '';
                                        echo form_dropdown('bloodgroupID', $bloodgroupArray,  set_value('bloodgroupID'), ' class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('bloodgroupID')?></span>
                                </div>
                                
                                <div class="form-group <?=form_error('bagno') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="bagno"><?=$this->lang->line('bloodstock_bagNo')?><span class="text-danger"> *</span></label>
                                    <input type="bagno" class="form-control <?=form_error('bagno') ? 'is-invalid' : '' ?> " id="bagno" name="bagno"  value="<?=set_value('bagno')?>">
                                    <span><?=form_error('bagno')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('bloodstock_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('bloodstock_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('bloodstock/bloodlist'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>