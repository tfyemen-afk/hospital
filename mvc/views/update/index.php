<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-registration"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_update')?></li>
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
                                <p class="title"><i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                            </div>
                        </div>
                        <form role="form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <div class="form-group <?=form_error('file') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="file"><?=$this->lang->line('update_file')?> <span class="text-danger">*</span></span></label>
                                    <div class="custom-file">
                                        <input accept=".zip" type="file" name="file" class="custom-file-input file-upload-input <?=form_error('file') ? 'is-invalid' : '' ?>" id="file-upload">
                                        <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('update_choose_file')?></label>
                                    </div>
                                    <span><?=form_error('file')?></span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button id="update" type="submit" class="btn btn-primary"><?=$this->lang->line('update_update')?></button>
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
                        <?php $this->load->view('update/table');?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>