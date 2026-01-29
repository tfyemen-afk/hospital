<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-leavecategory"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('leavecategory/index')?>"><?=$this->lang->line('menu_leavecategory')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('leavecategory_edit')?></li>
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
                            <p class="title"><i class='fa fa-edit'></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('leavecategory') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="leavecategory"><?=$this->lang->line('leavecategory_category')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('leavecategory') ? 'is-invalid' : '' ?>" id="leavecategory" name="leavecategory"  value="<?=set_value('leavecategory', $leavecategory->leavecategory)?>">
                                <span><?=form_error('leavecategory')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('leavecategory_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class='fa fa-table'></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('leavecategory/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article> 
                