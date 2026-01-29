<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-medicinewarehouse"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
                <ol class="breadcrumb themebreadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('medicinewarehouse/index')?>"><?=$this->lang->line('menu_medicinewarehouse')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('medicinewarehouse_edit')?></li>
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
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('medicinewarehouse_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $medicinewarehouse->name)?>">
                                <span><?=form_error('name')?></span>
                            </div>
                            <div class="form-group <?=form_error('code') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="code"><?=$this->lang->line('medicinewarehouse_code')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('code') ? 'is-invalid' : '' ?>" id="code" name="code"  value="<?=set_value('code', $medicinewarehouse->code)?>">
                                <span><?=form_error('code')?></span>
                            </div>
                            <div class="form-group <?=form_error('email') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="email"><?=$this->lang->line('medicinewarehouse_email')?></label>
                                <input type="text" class="form-control <?=form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email"  value="<?=set_value('email', $medicinewarehouse->email)?>">
                                <span><?=form_error('email')?></span>
                            </div>
                            <div class="form-group <?=form_error('phone') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="phone"><?=$this->lang->line('medicinewarehouse_phone')?></label>
                                <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : '' ?>" id="phone" name="phone"  value="<?=set_value('phone', $medicinewarehouse->phone)?>">
                                <span><?=form_error('phone')?></span>
                            </div>
                            <div class="form-group <?=form_error('address') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="address"><?=$this->lang->line('medicinewarehouse_address')?></label>
                                <textarea type="text" class="form-control <?=form_error('address') ? 'is-invalid' : '' ?>" id="address" name="address" ><?=set_value('address', $medicinewarehouse->address)?></textarea>
                                <span><?=form_error('address')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('medicinewarehouse_update')?></button>
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
                        <?php $this->load->view('medicinewarehouse/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>