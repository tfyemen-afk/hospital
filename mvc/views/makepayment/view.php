<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('makepayment_print'))?>
                <?=btn_sm_pdf('makepayment/printpreview/'.$makepayment->makepaymentID, $this->lang->line('makepayment_pdf_preview'))?>
                <?=btn_sm_mail($this->lang->line('makepayment_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('makepayment/add/'.$user->userID)?>"> <?=$this->lang->line('menu_makepayment')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('makepayment_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-md-3 user-profile-box">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img src="<?=imagelink($user->photo,'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">
                            <h3 class="profile-username text-center"><?=$user->name?></h3>
                            <p class="text-muted text-center"><?=inicompute($designation) ? $designation->designation : ''?></p>
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
                <div class="col-md-9 user-profile-details">
                    <div class="card">
                        <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="salary-tab" data-toggle="tab" href="#salary" role="tab" aria-controls="salary" aria-selected="true"><?=$this->lang->line('makepayment_salary')?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="salary" role="tabpanel" aria-labelledby="salary-tab">
                                <?php if($makepayment->salaryID == 1) { ?>
                                    <div class="row">
                                        <div class="col-sm-6 col-margin-bottom">
                                            <div class="info-box">
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_salary_grades")?>:&nbsp;</span>
                                                    <?=$salarytemplate->salary_grades?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_basic_salary")?>:&nbsp;</span>
                                                    <?=number_format($salarytemplate->basic_salary, 2)?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_overtime_rate")?>:&nbsp;</span>
                                                    <?=number_format($salarytemplate->overtime_rate, 2)?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_month")?>:&nbsp;</span>
                                                    <?=date('M Y', strtotime('01-'.$makepayment->month))?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_date")?>:&nbsp;</span>
                                                    <?=app_date($makepayment->create_date)?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_payment_method")?>:&nbsp;</span>
                                                    <?=($makepayment->payment_method == 1) ? $this->lang->line('makepayment_cash') : $this->lang->line('makepayment_cheque')?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_comments")?>:&nbsp;</span>
                                                    <?=$makepayment->comments?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="box box-border">
                                                <div class="box-header box-header-for-payroll">
                                                    <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('makepayment_allowances')?></h3>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-sm-12" id="allowances">
                                                            <div class="info-box">
                                                                <?php
                                                                    if(inicompute($salaryoptions)) {
                                                                        foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                            if($salaryoption->option_type == 1) { ?>
                                                                    <p>
                                                                        <span><?=$salaryoption->label_name?></span>
                                                                        <?=number_format($salaryoption->label_amount, 2)?>
                                                                    </p>
                                                                <?php } } } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="box box-border">
                                                <div class="box-header box-header-for-payroll">
                                                    <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('makepayment_deductions')?></h3>
                                                </div><!-- /.box-header -->
                                                <!-- form start -->
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-sm-12" id="deductions">
                                                            <div class="info-box">
                                                                <?php
                                                                    if(inicompute($salaryoptions)) {
                                                                        foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                            if($salaryoption->option_type == 2) { ?>
                                                                    <p>
                                                                        <span><?=$salaryoption->label_name?></span>
                                                                        <?=number_format($salaryoption->label_amount, 2)?>
                                                                    </p>
                                                                <?php } } } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 offset-sm-4">
                                            <div class="box box-border">
                                                <div class="box-header box-header-for-payroll">
                                                    <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('makepayment_total_salary_details')?></h3>
                                                </div>
                                                <div class="box-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('makepayment_gross_salary')?></td>
                                                            <td><?=number_format($grosssalary, 2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('makepayment_total_deduction')?></td>
                                                            <td><?=number_format($totaldeduction, 2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('makepayment_net_salary')?></td>
                                                            <td><?=number_format($netsalary, 2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="line-height"><b><?=$this->lang->line('makepayment_payment_amount')?></b></td>
                                                            <td><b><?=number_format($makepayment->payment_amount, 2)?></b></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } elseif($makepayment->salaryID == 2) { ?>
                                    <div class="row">
                                        <div class="col-sm-6 col-margin-bottom">
                                            <div class="info-box">
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_salary_grades")?>:&nbsp;</span>
                                                    <?=$hourly_salary->hourly_grades?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_hourly_salary")?>:&nbsp;</span>
                                                    <?=number_format($hourly_salary->hourly_rate, 2)?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_month")?>:&nbsp;</span>
                                                    <?=date('M Y', strtotime('01-'.$makepayment->month))?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_date")?>:&nbsp;</span>
                                                    <?=app_date($makepayment->create_date)?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_payment_method")?>:&nbsp;</span>
                                                    <?=($makepayment->payment_method == 1) ? $this->lang->line('makepayment_cash') : $this->lang->line('makepayment_cheque')?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("makepayment_comments")?>:&nbsp;</span>
                                                    <?=$makepayment->comments?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 offset-sm-4">
                                            <div class="box box-border">
                                                <div class="box-header box-header-for-payroll">
                                                    <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('makepayment_total_salary_details')?></h3>
                                                </div>
                                                <div class="box-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('makepayment_gross_salary')?></td>
                                                            <td><?=number_format($grosssalary, 2)?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('makepayment_total_deduction')?></td>
                                                            <td><?=number_format($totaldeduction, 2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('makepayment_total_hours')?></td>
                                                            <td><?=number_format($makepayment->total_hours,2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            $netsalary_label = $makepayment->total_hours .'*'. $makepayment->net_salary;
                                                            $netsalary_val   = $makepayment->total_hours * $makepayment->net_salary;
                                                            ?>
                                                            <td class="line-height"><?=$this->lang->line('makepayment_net_salary')?> <span class="text-danger">( <?=$netsalary_label?> )</span></td>
                                                            <td><?=number_format($netsalary_val, 2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="line-height"><b><?=$this->lang->line('makepayment_payment_amount')?></b></td>
                                                            <td><b><?=number_format($makepayment->payment_amount, 2)?></b></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

<div class="modal" id="mail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title"><?=$this->lang->line('makepayment_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('makepayment_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('makepayment_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('makepayment_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('makepayment_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>