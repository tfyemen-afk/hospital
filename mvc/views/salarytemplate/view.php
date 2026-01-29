<article class="content">
     <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('salarytemplate_print'))?>
                <?=btn_sm_pdf('salarytemplate/printpreview/'.$salarytemplate->salarytemplateID, $this->lang->line('salarytemplate_pdf_preview'))?>
                <?=btn_sm_edit('salarytemplate_edit', 'salarytemplate/edit/'.$salarytemplate->salarytemplateID, $this->lang->line('salarytemplate_edit'))?>
                <?=btn_sm_mail($this->lang->line('salarytemplate_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('salarytemplate/index')?>"> <?=$this->lang->line('menu_salarytemplate')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('salarytemplate_view')?></li>
                  </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-block">
                        <div class="row">
                            <div class="col-sm-6 payroll-margin-bottom">
                                <div class="info-box">
                                    <p class="margin">
                                        <span><?=$this->lang->line("salarytemplate_salary_grades")?></span>
                                        <?=$salarytemplate->salary_grades?>
                                    </p>

                                    <p class="margin">
                                        <span><?=$this->lang->line("salarytemplate_basic_salary")?></span>
                                        <?=number_format($salarytemplate->basic_salary, 2)?>
                                    </p>

                                    <p class="margin">
                                        <span><?=$this->lang->line("salarytemplate_overtime_rate")?></span>
                                        <?=number_format($salarytemplate->overtime_rate, 2)?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="box box-border">
                                    <div class="box-header box-header-for-payroll">
                                        <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('salarytemplate_allowances')?></h3>
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
                                                    <?php  } } } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="box box-border">
                                    <div class="box-header box-header-for-payroll">
                                        <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('salarytemplate_deductions')?></h3>
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
                                        <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('salarytemplate_total_salary_details')?></h3>
                                    </div>
                                    <div class="box-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="salary-template-label"><?=$this->lang->line('salarytemplate_gross_salary')?></td>
                                                <td class="salary-template-value"><?=number_format($grosssalary, 2)?></td>
                                            </tr>
                                            <tr>
                                                <td class="salary-template-label"><?=$this->lang->line('salarytemplate_total_deduction')?></td>
                                                <td class="salary-template-value"><?=number_format($totaldeduction, 2)?></td>
                                            </tr>
                                            <tr>
                                                <td class="salary-template-label"><?=$this->lang->line('salarytemplate_net_salary')?></td>
                                                <td class="salary-template-value"><b><?=number_format($netsalary, 2)?></b></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
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
                <h6 class="mdoal-title"><?=$this->lang->line('salarytemplate_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('salarytemplate_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('salarytemplate_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('salarytemplate_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('salarytemplate_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>