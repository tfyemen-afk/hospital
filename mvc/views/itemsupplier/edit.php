<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-itemsupplier"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('itemsupplier/index')?>"><?=$this->lang->line('menu_itemsupplier')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('itemsupplier_edit')?></li>
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
                            <p class="title"> <i class="fa fa-edit"></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('companyname') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="companyname"><?=$this->lang->line('itemsupplier_company_name')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('companyname') ? 'is-invalid' : '' ?>" id="companyname" name="companyname"  value="<?=set_value('companyname', $itemsupplier->companyname)?>">
                                <span><?=form_error('companyname')?></span>
                            </div>
                            <div class="form-group <?=form_error('suppliername') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="suppliername"><?=$this->lang->line('itemsupplier_supplier_name')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('suppliername') ? 'is-invalid' : '' ?>" id="suppliername" name="suppliername"  value="<?=set_value('suppliername', $itemsupplier->suppliername)?>">
                                <span><?=form_error('suppliername')?></span>
                            </div>
                            <div class="form-group <?=form_error('email') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="email"><?=$this->lang->line('itemsupplier_email')?></label>
                                <input type="text" class="form-control <?=form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email"  value="<?=set_value('email', $itemsupplier->email)?>">
                                <span><?=form_error('email')?></span>
                            </div>
                            <div class="form-group <?=form_error('phone') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="phone"><?=$this->lang->line('itemsupplier_phone')?></label>
                                <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : '' ?>" id="phone" name="phone"  value="<?=set_value('phone', $itemsupplier->phone)?>">
                                <span><?=form_error('phone')?></span>
                            </div>
                            <div class="form-group <?=form_error('address') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="address"><?=$this->lang->line('itemsupplier_address')?></label>
                                <textarea type="text" class="form-control <?=form_error('address') ? 'is-invalid' : '' ?>" id="address" name="address" rows="3"><?=set_value('address', $itemsupplier->address)?></textarea>
                                <span><?=form_error('address')?></span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('itemsupplier_update')?></button>
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
                        <?php $this->load->view('itemsupplier/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>