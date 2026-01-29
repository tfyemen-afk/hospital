<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-medicinemanufacturer"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('medicinemanufacturer/index')?>"><?=$this->lang->line('menu_medicinemanufacturer')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('medicinemanufacturer_edit')?></li>
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
                            <p class="title"><i class="fa fa-edit"></i>&nbsp; <?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('medicinemanufacturer_name')?> <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name" value="<?=set_value('name',$medicinemanufacturer->name)?>">
                                <span class="text-danger"><?=form_error('name')?></span>
                            </div>
                            <div class="form-group <?=form_error('supplier_name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="supplier_name"><?=$this->lang->line('medicinemanufacturer_supplier_name')?> <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('supplier_name') ? 'is-invalid' : '' ?>" id="supplier_name" name="supplier_name" value="<?=set_value('supplier_name',$medicinemanufacturer->supplier_name)?>">
                                <span class="text-danger"><?=form_error('supplier_name')?></span>
                            </div>
                            <div class="form-group <?=form_error('email') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="email"><?=$this->lang->line('medicinemanufacturer_email')?> <span class="text-danger"> *</span></label>
                                <input type="email" class="form-control <?=form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email" value="<?=set_value('email',$medicinemanufacturer->email)?>">
                                <span class="text-danger"><?=form_error('email')?></span>
                            </div>
                            <div class="form-group <?=form_error('phone') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="phone"><?=$this->lang->line('medicinemanufacturer_phone')?> <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : '' ?>" id="phone" name="phone" value="<?=set_value('phone',$medicinemanufacturer->phone)?>">
                                <span class="text-danger"><?=form_error('phone')?></span>
                            </div>
                            <div class="form-group <?=form_error('address') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="address"><?=$this->lang->line('medicinemanufacturer_address')?> <span class="text-danger"> *</span></label>
                                <textarea type="text" class="form-control <?=form_error('address') ? 'is-invalid' : '' ?>" id="address" name="address"><?=set_value('address',$medicinemanufacturer->address)?></textarea>
                                <span class="text-danger"><?=form_error('address')?></span>
                            </div>
                            <div class="form-group <?=form_error('details') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="details"><?=$this->lang->line('medicinemanufacturer_details')?></label>
                                <textarea type="text" class="form-control <?=form_error('details') ? 'is-invalid' : '' ?>" id="details" name="details"><?=set_value('details',$medicinemanufacturer->details)?></textarea>
                                <span class="text-danger"><?=form_error('details')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('medicinemanufacturer_update')?></button>
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
                        <?php $this->load->view('medicinemanufacturer/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
