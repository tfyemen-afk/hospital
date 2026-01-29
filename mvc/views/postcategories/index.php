<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-anchor"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_postcategories')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('postcategories_add')) { ?>
                <div class="col-sm-4">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="header-block">
                                <p class="title"> <i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                            </div>
                        </div>
                        <form role="form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <div class="form-group <?=form_error('postcategories') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="name"><?=$this->lang->line('postcategories_name')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('postcategories') ? 'is-invalid' : '' ?>" id="postcategories" name="postcategories"  value="<?=set_value('postcategories')?>">
                                    <span><?=form_error('postcategories')?></span>
                                </div>

                                <div class="form-group <?=form_error('postdescription') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="postdescription"><?=$this->lang->line('postcategories_description')?></label>
                                    <textarea class="form-control <?=form_error('postdescription') ? 'is-invalid' : '' ?>" id="postdescription" name="postdescription" rows="5" ><?=set_value('postdescription')?></textarea>
                                    <span><?=form_error('postdescription')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('postcategories_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('postcategories_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('postcategories/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
                