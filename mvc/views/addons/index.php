<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-crosshairs"></i> <?=$this->lang->line('panel_title')?></h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_addons')?></li>
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
                            <p class="title"> <i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block"> 
                            <div class="form-group <?=form_error('file') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="file"><?=$this->lang->line('addons_file')?></label>
                                <span class="text-danger">*</span>
                                <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input file-upload-input <?=form_error('file') ? 'is-invalid' : '' ?>" id="file-upload">
                                    <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('addons_choose_file')?></label>
                                </div>
                                <span><?=form_error('file')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('addons_upload')?></button>
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
                        <div class="row">
                            <?php if(inicompute($addons)) { foreach($addons as $addon) { ?>
                                <div class="col-sm-4">
                                    <div class="box card-box-ini">
                                        <img class="card-img-ini" width="100%" src="<?=base_url('uploads/addons/'.$addon->slug.'/src/image/'. $addon->preview_image);?>" alt="<?=$addon->package_name?>">
                                        <div class="box-body">
                                            <h3 class="box-title"><?=$addon->package_name?></h3>
                                            <h4 class="box-title"><?=$addon->version?></h4>
                                            <p class="box-text"><?=namesorting($addon->description, 100)?></p>
                                            <a href="<?=base_url('addons/rollback/'.$addon->addonsID)?>" class="btn btn-danger"><i class="fa fa-trash"></i> <?=$this->lang->line('addons_delete')?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php } } else { ?>
                                <div class="col-sm-12">
                                    <?=$this->lang->line('addons_not_found')?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>