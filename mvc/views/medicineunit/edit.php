<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-medicineunit"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('medicineunit/index')?>"><?=$this->lang->line('menu_medicineunit')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('medicineunit_edit')?></li>
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
                            <div class="form-group <?=form_error('medicineunit') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="medicineunit"><?=$this->lang->line('medicineunit_unit')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('medicineunit') ? 'is-invalid' : '' ?>" id="medicineunit" name="medicineunit" value="<?=set_value('medicineunit', $medicineunit->medicineunit)?>">
                                <span class="text-danger"><?=form_error('medicineunit')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('medicineunit_update')?></button>
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
                        <?php $this->load->view('medicineunit/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
