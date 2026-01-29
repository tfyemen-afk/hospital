<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-calendar-check-o"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
              <ol class="breadcrumb themebreadcrumb pull-right">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item"><a href="<?=site_url('department/index')?>"> <?=$this->lang->line('menu_department')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('department_edit')?></li>
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
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('department_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $department->name)?>">
                                <span><?=form_error('name')?></span>
                            </div>
                            <div class="form-group <?=form_error('description') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="description"><?=$this->lang->line('department_description')?> <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control <?=form_error('description') ? 'is-invalid' : '' ?>" cols="30" rows="10"><?=$department->description?></textarea>
                                <span><?=form_error('description')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('department_update')?></button>
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
                        <?php $this->load->view('department/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>