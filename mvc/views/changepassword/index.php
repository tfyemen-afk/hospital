<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-lock"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('panel_title')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-edit"></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('oldpassword') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="type"><?=$this->lang->line('changepassword_oldpassword')?><span class="text-danger"> *</span></label>
                                <input type="password" name="oldpassword" value="<?=set_value('oldpassword')?>" class="form-control <?=form_error('oldpassword') ? 'is-invalid' : ''?>">
                                <span id="oldpassword-error"><?=form_error('oldpassword')?></span>
                            </div>
                            <div class="form-group <?=form_error('newpassword') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="type"><?=$this->lang->line('changepassword_newpassword')?><span class="text-danger"> *</span></label>
                                <input type="password" name="newpassword" value="<?=set_value('newpassword')?>" class="form-control <?=form_error('newpassword') ? 'is-invalid' : ''?>">
                                <span id="newpassword-error"><?=form_error('newpassword')?></span>
                            </div>
                            <div class="form-group <?=form_error('repetpassword') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="type"><?=$this->lang->line('changepassword_repetpassword')?><span class="text-danger"> *</span></label>
                                <input type="password" name="repetpassword" value="<?=set_value('repetpassword')?>" class="form-control <?=form_error('repetpassword') ? 'is-invalid' : ''?>">
                                <span id="repetpassword-error"><?=form_error('repetpassword')?></span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="damageandexpireAdd"><?=$this->lang->line('changepassword_password')?></button>
                        </div>
                    </form>
                </div>
        </div>
    </section>
</article>