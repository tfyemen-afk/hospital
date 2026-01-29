<article class="content">
      <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('managesalary_print'))?>
                <?=btn_sm_pdf('managesalary/printpreview/'.$managesalary->managesalaryID, $this->lang->line('managesalary_pdf_preview'))?>
                <?=btn_sm_modal_edit('managesalary_edit', $managesalary->managesalaryID, $this->lang->line('managesalary_edit'))?>
                <?=btn_sm_mail($this->lang->line('managesalary_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('managesalary/index')?>"> <?=$this->lang->line('menu_managesalary')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('managesalary_view')?></li>
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
                            <img src="<?=pdfimagelink($user->photo,'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">                    
                            <h3 class="profile-username text-center"><?=$user->name?></h3>
                            <p class="text-muted text-center"><?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('managesalary_gender')?></b> <a class="pull-right"><?=($user->gender == '1')? $this->lang->line('managesalary_male'): $this->lang->line('managesalary_female') ?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('managesalary_dob')?></b> <a class="pull-right"><?=app_date($user->dob)?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('managesalary_phone')?></b> <a class="pull-right"><?=$user->phone?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 user-profile-details">
                    <div class="card">
                        <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="salary-tab" data-toggle="tab" href="#salary" role="tab" aria-controls="salary" aria-selected="true"><?=$this->lang->line('managesalary_info')?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="salary" role="tabpanel" aria-labelledby="salary-tab">
                                <?php if($managesalary->salary == '1') { ?>
                                    <div class="row">
                                        <div class="col-sm-6 col-margin-bottom">
                                            <div class="info-box">
                                                <p class="margin">
                                                    <span><?=$this->lang->line("managesalary_salary_grades")?>:&nbsp;</span>
                                                    <?=$salarytemplate->salary_grades?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("managesalary_basic_salary")?>:&nbsp;</span>
                                                    <?=number_format($salarytemplate->basic_salary, 2)?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("managesalary_overtime_rate")?>:&nbsp;</span>
                                                    <?=number_format($salarytemplate->overtime_rate, 2)?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="box box-border">
                                                <div class="box-header box-header-for-payroll">
                                                    <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('managesalary_allowances')?></h3>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-sm-12" id="allowances">
                                                            <div class="info-box">
                                                                <?php 
                                                                    if(inicompute($salaryoptions)) {
                                                                        foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                            if($salaryoption->option_type == 1) {
                                                                ?>
                                                                    <p>
                                                                        <span><?=$salaryoption->label_name?></span>
                                                                        <?=number_format($salaryoption->label_amount, 2)?>
                                                                    </p>
                                                                <?php        
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="box box-border">
                                                <div class="box-header box-header-for-payroll">
                                                    <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('managesalary_deductions')?></h3>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-sm-12" id="deductions">
                                                            <div class="info-box">
                                                                <?php 
                                                                    if(inicompute($salaryoptions)) {
                                                                        foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                            if($salaryoption->option_type == 2) {
                                                                ?>
                                                                    <p>
                                                                        <span><?=$salaryoption->label_name?></span>
                                                                        <?=number_format($salaryoption->label_amount, 2)?>
                                                                    </p>
                                                                <?php        
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
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
                                                    <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('managesalary_total_salary_details')?></h3>
                                                </div>
                                                <div class="box-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('managesalary_gross_salary')?></td>
                                                            <td><?=number_format($grosssalary, 2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('managesalary_total_deduction')?></td>
                                                            <td><?=number_format($totaldeduction, 2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('managesalary_net_salary')?></td>
                                                            <td><b><?=number_format($netsalary, 2)?></b></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } elseif($managesalary->salary == 2) { ?>
                                    <div class="row">
                                        <div class="col-sm-6 col-margin-bottom">
                                            <div class="info-box">
                                                <p class="margin">
                                                    <span><?=$this->lang->line("managesalary_salary_grades")?>:&nbsp;</span>
                                                    <?=$hourly_salary->hourly_grades?>
                                                </p>
                                                <p class="margin">
                                                    <span><?=$this->lang->line("managesalary_hourly_rate")?>:&nbsp;</span>
                                                    <?=number_format($hourly_salary->hourly_rate, 2)?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8 offset-sm-4">
                                            <div class="box box-border">
                                                <div class="box-header box-header-for-payroll">
                                                    <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('managesalary_total_salary_details')?></h3>
                                                </div>
                                                <div class="box-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('managesalary_gross_salary')?></td>
                                                            <td><?=number_format($grosssalary, 2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('managesalary_total_deduction')?></td>
                                                            <td><?=number_format($totaldeduction, 2)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="line-height"><?=$this->lang->line('managesalary_net_salary')?></td>
                                                            <td><b><?=number_format($netsalary, 2)?></b></td>
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

    <?php if(permissionChecker('managesalary_edit')) { ?>
        <div class="modal" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="mdoal-title">
                            <?=$this->lang->line('managesalary_edit')?> <?=$this->lang->line('panel_title')?>
                        </h6>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form role="form" method="POST">
                        <div class="modal-body">
                            <div class="form-group" id="error_salary_edit_div">
                                <label><?=$this->lang->line('managesalary_salary')?><span class="text-danger"> *</span></label>
                                <?php
                                $array = array(
                                    "0" => $this->lang->line('managesalary_select_salary'),
                                    '1' => $this->lang->line('managesalary_monthly_salary'),
                                    '2' => $this->lang->line('managesalary_hourly_salary')
                                );
                                echo form_dropdown("salary", $array, set_value("salary"), "id='salary_edit' class='form-control select3'");
                                ?>
                                <span class="text-danger" id="error_salary_edit"></span>
                            </div>
                            <div class="form-group" id="error_salary_template_edit_div">
                                <label><?=$this->lang->line('managesalary_template')?><span class="text-danger"> *</span></label>
                                <?php
                                $array = array(0 => $this->lang->line("managesalary_select_template"));
                                echo form_dropdown("template", $array, set_value("template"), "id='template_edit' class='form-control select4'");
                                ?>
                                <span class="text-danger" id="error_salary_template_edit"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('managesalary_close')?></button>
                            <button type="button" class="btn btn-primary edit_managesalary"><?=$this->lang->line('managesalary_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</article>


<div class="modal" id="mail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title"><?=$this->lang->line('managesalary_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('managesalary_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('managesalary_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('managesalary_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('managesalary_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>