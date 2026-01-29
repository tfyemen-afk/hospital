<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-income"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('income/index')?>"><?=$this->lang->line('menu_income')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('income_edit')?></li>
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
                            <div class="form-group <?=form_error('income_name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('income_name')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('income_name') ? 'is-invalid' : '' ?>" id="income_name" name="income_name"  value="<?=set_value('income_name', $income->name)?>">
                                <span><?=form_error('income_name')?></span>
                            </div>

                            <div class="form-group <?=form_error('income_date') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('income_date')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('income_date') ? 'is-invalid' : '' ?> datepicker" id="income_date" name="income_date"  value="<?=set_value('income_date', date('d-m-Y', strtotime($income->date)))?>">
                                <span><?=form_error('income_date')?></span>
                            </div>

                            <div class="form-group <?=form_error('income_amount') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('income_amount')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('income_amount') ? 'is-invalid' : '' ?>" id="income_amount" name="income_amount"  value="<?=set_value('income_amount', $income->amount)?>">
                                <span><?=form_error('income_amount')?></span>
                            </div>

                            <div class="form-group <?=form_error('income_file') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="income_file"><?=$this->lang->line('income_file')?></label>
                                <div class="custom-file">
                                    <input type="file" name="income_file" class="custom-file-input file-upload-input <?=form_error('income_file') ? 'is-invalid' : '' ?>" id="file-upload">
                                    <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('income_choose_file')?></label>
                                </div>
                                <span><?=form_error('income_file')?></span>
                            </div>

                            <div class="form-group <?=form_error('income_note') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="income_note"><?=$this->lang->line('income_note')?></label>
                                <textarea type="text" class="form-control <?=form_error('income_note') ? 'is-invalid' : '' ?>" id="income_note" name="income_note" rows="5"><?=set_value('income_note', $income->note)?></textarea>
                                <span><?=form_error('income_note')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('income_update')?></button>
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
                        <?php $this->load->view('income/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
