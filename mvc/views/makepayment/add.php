<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-money"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
                <ol class="breadcrumb themebreadcrumb pull-right">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('makepayment/index')?>"><?=$this->lang->line('menu_makepayment')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('makepayment_add')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-custom">
                            <div class="card-header">
                                <div class="header-block">
                                    <p class="title"><i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('makepayment_filter')?></p>
                                </div>
                            </div>

                            <div class="card-block">
                                <img src="<?=imagelink($user->photo,'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">
                                <h3 class="profile-username text-center"><?=$user->name?></h3>
                                <p class="text-muted text-center"><?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : '' ?></p>

                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('makepayment_gender')?></b> <a class="pull-right"><?=($user->gender == '1')? $this->lang->line('makepayment_male'): $this->lang->line('makepayment_female') ?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('makepayment_dob')?></b> <a class="pull-right"><?=app_date($user->dob)?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('makepayment_phone')?></b> <a class="pull-right"><?=$user->phone?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <form method="post">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card card-custom">
                                <div class="card-block">
                                    <div class="form-group <?=form_error('gross_salary') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="gross_salary"><?=$this->lang->line('makepayment_gross_salary')?><span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control <?=form_error('gross_salary') ? 'is-invalid' : '' ?>" id="gross_salary" name="gross_salary"  value="<?=set_value('gross_salary', $grosssalary)?>" readonly />
                                        <span><?=form_error('gross_salary')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('total_deduction') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="total_deduction"><?=$this->lang->line('makepayment_total_deduction')?><span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control <?=form_error('total_deduction') ? 'is-invalid' : '' ?>" id="total_deduction" name="total_deduction"  value="<?=set_value('total_deduction', $totaldeduction)?>" readonly />
                                        <span><?=form_error('total_deduction')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('net_salary') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="net_salary"><?=$this->lang->line('makepayment_net_salary')?><span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control <?=form_error('net_salary') ? 'is-invalid' : '' ?>" id="net_salary" name="net_salary"  value="<?=set_value('net_salary', $netsalary)?>" readonly>
                                        <span><?=form_error('net_salary')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('month') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="month"><?=$this->lang->line('makepayment_month')?><span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control monthpicker <?=form_error('month') ? 'is-invalid' : '' ?>" id="month" name="month"  value="<?=set_value('month')?>">
                                        <span><?=form_error('month')?></span>
                                    </div>
                                    <?php if($managesalary->salary == 2) { ?>
                                        <div class="form-group <?=form_error('total_hours') ? 'text-danger' : '' ?>">
                                            <label class="control-label" for="total_hours"><?=$this->lang->line('makepayment_total_hours')?><span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control <?=form_error('total_hours') ? 'is-invalid' : '' ?>" id="total_hours" name="total_hours"  value="<?=set_value('total_hours')?>">
                                            <span><?=form_error('total_hours')?></span>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group <?=form_error('payment_amount') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="payment_amount"><?=$this->lang->line('makepayment_payment_amount')?><span class="text-danger"> *</span></label> <?php if($managesalary->salary == 2) { echo '<span id="hourdis"></span>'; } ?>
                                        <input type="text" class="form-control <?=form_error('payment_amount') ? 'is-invalid' : '' ?>" id="payment_amount" name="payment_amount"  value="<?=set_value('payment_amount', $netsalary)?>">
                                        <span><?=form_error('payment_amount')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('payment_method') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="payment_method"><?=$this->lang->line('makepayment_payment_method')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $paymentMethod['0'] = $this->lang->line('makepayment_select_payment_method');
                                        $paymentMethod['1'] = $this->lang->line('makepayment_cash');
                                        $paymentMethod['2'] = $this->lang->line('makepayment_cheque');
                                        $errorClass         = form_error('payment_method') ? 'is-invalid' : '';
                                        echo form_dropdown('payment_method', $paymentMethod, set_value('payment_method'), 'class="form-control payment_method select2 '.$errorClass.'"')?>
                                        <span><?=form_error('payment_method')?></span>
                                    </div>
                                    <div class="form-group <?=form_error('comments') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="comments"><?=$this->lang->line('makepayment_comments')?></label>
                                        <input type="text" class="form-control <?=form_error('comments') ? 'is-invalid' : '' ?>" id="comments" name="comments"  value="<?=set_value('comments')?>">
                                        <span><?=form_error('comments')?></span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary"><?=$this->lang->line('makepayment_add')?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('makepayment_filter_data')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('makepayment/payment_table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>