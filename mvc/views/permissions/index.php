<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-permission"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
                <ol class="breadcrumb themebreadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_permissions')?></li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="<?=site_url('permissions/save')?>" method="POST">
        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
        <section class="section">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="header-block">
                                <p class="title"><i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('permissions_filter')?></p>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="form-group">
                                <label for="roleID" class="control-label"><?=$this->lang->line('permissions_role')?> <span class="text-danger"> *</span></label>
                                <?php
                                    $rolesArray['0'] = $this->lang->line('permissions_please_select');
                                    if(inicompute($roles)) {
                                        foreach ($roles as $role) {
                                            $rolesArray[$role->roleID] = $role->role;
                                        }
                                    }
                                    $errorClass = form_error('roleID') ? 'is-invalid' : '';
                                    echo form_dropdown('roleID', $rolesArray,  set_value('roleID', $roleID), ' class="form-control select2 '.$errorClass.'" id="roleID"')
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
                                <p class="title"><i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('permissions_filter_data')?></p>
                            </div>
                        </div>
                        <div class="card-block">
                            <div id="hide-table">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?=$this->lang->line('permissions_slno')?></th>
                                            <th><?=$this->lang->line('permissions_feature_name')?></th>
                                            <th><?=$this->lang->line('permissions_add')?></th>
                                            <th><?=$this->lang->line('permissions_edit')?></th>
                                            <th><?=$this->lang->line('permissions_delete')?></th>
                                            <th><?=$this->lang->line('permissions_view')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(inicompute($permissionlogrowArray)) { ?>
                                            <?php foreach($permissionlogrowArray as $permissionlogrow) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('permissions_slno')?>">
                                                        <input type="checkbox" id="<?=$permissionlogrow->name?>" name="<?=$permissionlogrow->name?>" value="<?=$permissionlogrow->permissionlogID?>"  <?=isset($permissions[$permissionlogrow->permissionlogID]) ? 'checked' : ''?> onclick="processCheck(this);" class="mainmodule"/>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('permissions_feature_name')?>"><?=ucfirst($permissionlogrow->description)?></td>
                                                    <td data-title="<?=$this->lang->line('permissions_add')?>">
                                                        <?php $permissionadd = $permissionlogrow->name.'_add'; if(isset($permissionlogArray[$permissionadd])) { ?>
                                                            <input type="checkbox" id="<?=$permissionadd?>" name="<?=$permissionadd?>" value="<?=$permissionlogArray[$permissionadd]?>" <?=isset($permissions[$permissionlogArray[$permissionadd]]) ? 'checked' : ''?> />
                                                        <?php } else { echo "&nbsp;"; } ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('permissions_edit')?>">
                                                        <?php $permissionedit = $permissionlogrow->name.'_edit'; if(isset($permissionlogArray[$permissionedit])) { ?>
                                                            <input type="checkbox" id="<?=$permissionedit?>" name="<?=$permissionedit?>" value="<?=$permissionlogArray[$permissionedit]?>" <?=isset($permissions[$permissionlogArray[$permissionedit]]) ? 'checked' : ''?> />
                                                        <?php } else { echo "&nbsp;"; } ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('permissions_delete')?>">
                                                        <?php $permissiondelete = $permissionlogrow->name.'_delete'; if(isset($permissionlogArray[$permissiondelete])) { ?>
                                                            <input type="checkbox" id="<?=$permissiondelete?>" name="<?=$permissiondelete?>" value="<?=$permissionlogArray[$permissiondelete]?>" <?=isset($permissions[$permissionlogArray[$permissiondelete]]) ? 'checked' : ''?> />
                                                        <?php } else { echo "&nbsp;"; } ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('permissions_view')?>">
                                                        <?php $permissionview = $permissionlogrow->name.'_view'; if(isset($permissionlogArray[$permissionview])) { ?>
                                                            <input type="checkbox" id="<?=$permissionview?>" name="<?=$permissionview?>" value="<?=$permissionlogArray[$permissionview]?>" <?=isset($permissions[$permissionlogArray[$permissionview]]) ? 'checked' : ''?> />
                                                        <?php } else { echo "&nbsp;"; } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php if(inicompute($permissionlogrowArray)) { ?>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('permissions_save')?></button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    </form>
</article>
