<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-permissionlog"></i><?=$this->lang->line('panel_title')?></h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
              <ol class="breadcrumb themebreadcrumb pull-right">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('permissionlog_home')?></a></li>
                <li class="breadcrumb-item"><a href="<?=site_url('permissionlog/index')?>"></i> <?=$this->lang->line('menu_permissionlog')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('permissionlog_edit')?></li>
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
                            <div class="card-block">
                                <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="name"><?=$this->lang->line('permissionlog_name')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $permissionlog->name)?>">
                                    <span><?=form_error('name')?></span>
                                </div>
                                <div class="form-group <?=form_error('description') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="description"><?=$this->lang->line('permissionlog_description')?> <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" cols="30" rows="3" class="form-control <?=form_error('description') ? 'is-invalid' : '' ?>"><?=set_value('description', $permissionlog->description)?></textarea>
                                    <span><?=form_error('description')?></span>
                                </div>
                                <div class="form-group <?=form_error('active') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="active"><?=$this->lang->line('permissionlog_active')?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?=form_error('active') ? 'is-invalid' : '' ?>" id="active" name="active"  value="<?=set_value('active', $permissionlog->active)?>">
                                    <span><?=form_error('active')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('permissionlog_update')?></button>
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
                        <?php $this->load->view('permissionlog/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>