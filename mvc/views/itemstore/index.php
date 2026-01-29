<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-itemstore"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_itemstore')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('itemstore_add')) { ?>
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
                                <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="name"><?=$this->lang->line('itemstore_name')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name')?>">
                                    <span><?=form_error('name')?></span>
                                </div>
                                <div class="form-group <?=form_error('code') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="code"><?=$this->lang->line('itemstore_code')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('code') ? 'is-invalid' : '' ?>" id="code" name="code"  value="<?=set_value('code')?>">
                                    <span><?=form_error('code')?></span>
                                </div>
                                <div class="form-group <?=form_error('incharge') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="incharge"><?=$this->lang->line('itemstore_in_charge')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('incharge') ? 'is-invalid' : '' ?>" id="incharge" name="incharge"  value="<?=set_value('incharge')?>">
                                    <span><?=form_error('incharge')?></span>
                                </div>
                                <div class="form-group <?=form_error('email') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="email"><?=$this->lang->line('itemstore_email')?></label>
                                    <input type="text" class="form-control <?=form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email"  value="<?=set_value('email')?>">
                                    <span><?=form_error('email')?></span>
                                </div>
                                <div class="form-group <?=form_error('phone') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="phone"><?=$this->lang->line('itemstore_phone')?></label>
                                    <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : '' ?>" id="phone" name="phone"  value="<?=set_value('phone')?>">
                                    <span><?=form_error('phone')?></span>
                                </div>
                                <div class="form-group <?=form_error('location') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="location"><?=$this->lang->line('itemstore_location')?></label>
                                    <textarea type="text" class="form-control <?=form_error('location') ? 'is-invalid' : '' ?>" id="location" name="location" rows="3"><?=set_value('location')?></textarea>
                                    <span><?=form_error('location')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('itemstore_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('itemstore_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('itemstore/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
                