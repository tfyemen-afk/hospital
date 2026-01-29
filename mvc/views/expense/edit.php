<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-expense"></i><?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('expense/index')?>"><?=$this->lang->line('menu_expense')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('expense_edit')?></li>
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
                            <div class="form-group <?=form_error('expense_name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('expense_name')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('expense_name') ? 'is-invalid' : '' ?>" id="expense_name" name="expense_name"  value="<?=set_value('expense_name', $expense->name)?>">
                                <span><?=form_error('expense_name')?></span>
                            </div>

                            <div class="form-group <?=form_error('expense_date') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('expense_date')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('expense_date') ? 'is-invalid' : '' ?> datepicker" id="expense_date" name="expense_date"  value="<?=set_value('expense_date', date('d-m-Y', strtotime($expense->date)))?>">
                                <span><?=form_error('expense_date')?></span>
                            </div>

                            <div class="form-group <?=form_error('expense_amount') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('expense_amount')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('expense_amount') ? 'is-invalid' : '' ?>" id="expense_amount" name="expense_amount"  value="<?=set_value('expense_amount', $expense->amount)?>">
                                <span><?=form_error('expense_amount')?></span>
                            </div>

                            <div class="form-group <?=form_error('expense_file') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="expense_file"><?=$this->lang->line('expense_file')?></label>
                                <div class="custom-file">
                                    <input type="file" name="expense_file" class="custom-file-input file-upload-input <?=form_error('expense_file') ? 'is-invalid' : '' ?>" id="file-upload">
                                    <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('expense_choose_file')?></label>
                                </div>
                                <span><?=form_error('expense_file')?></span>
                            </div>

                            <div class="form-group <?=form_error('expense_note') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="expense_note"><?=$this->lang->line('expense_note')?></label>
                                <textarea type="text" class="form-control <?=form_error('expense_note') ? 'is-invalid' : '' ?>" id="expense_note" name="expense_note" rows="5"><?=set_value('expense_note', $expense->note)?></textarea>
                                <span><?=form_error('expense_note')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('expense_update')?></button>
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
                        <?php $this->load->view('expense/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
                