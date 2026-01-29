<?php
    $monthArray = array(
      "01" => "jan",
      "02" => "feb",
      "03" => "mar",
      "04" => "apr",
      "05" => "may",
      "06" => "jun",
      "07" => "jul",
      "08" => "aug",
      "09" => "sep",
      "10" => "oct",
      "11" => "nov",
      "12" => "dec"
    );
?>
<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('user_print'))?>
                <?=btn_sm_pdf('user/printpreview/'.$profile->userID, $this->lang->line('user_pdf_preview'))?>
                <?=btn_sm_edit('user_edit', 'user/edit/'.$profile->userID, $this->lang->line('user_edit'))?>
                <?=btn_sm_mail($this->lang->line('user_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('user/index')?>"> <?=$this->lang->line('menu_user')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('user_view')?></li>
                  </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-3 user-profile-box">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img src="<?=imagelink($profile->photo,'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">
                            <h3 class="profile-username text-center"><?=$profile->name?></h3>
                            <p class="text-muted text-center"><?=isset($designations[$profile->designationID]) ? $designations[$profile->designationID] : ''?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('user_gender')?></b> <a class="pull-right"><?=($profile->gender == '1')? $this->lang->line('user_male'): $this->lang->line('user_female')?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('user_dob')?></b> <a class="pull-right"><?=app_date($profile->dob)?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('user_phone')?></b> <a class="pull-right"><?=$profile->phone?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 user-profile-details">
                    <div class="card">
                        <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true"><?=$this->lang->line('user_profile')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="attendance-tab" data-toggle="tab" href="#attendance" role="tab" aria-controls="attendance" aria-selected="true"><?=$this->lang->line('user_attendance')?></a>
                            </li>
                            <?php if(inicompute($managesalary)) { ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary" role="tab" aria-controls="salary" aria-selected="true"><?=$this->lang->line('user_salary')?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="true"><?=$this->lang->line('user_payment')?></a>
                                </li>
                            <?php } ?>
                            <li class="nav-item">
                                <a class="nav-link" id="document-tab" data-toggle="tab" href="#document" role="tab" aria-controls="document" aria-selected="true"><?=$this->lang->line('user_document')?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active overflow-hidden" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="profile-view-dis">
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('user_jod')?> </span>: <?=app_date($profile->jod)?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('user_religion')?> </span>: <?=$profile->religion?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('user_email')?> </span>: <?=$profile->email?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('user_address')?> </span>: <?=$profile->address?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('user_description')?> </span>: <?=$profile->description?></p>
                                    </div>
                                    <?php if($profile->designationID == 3) { ?>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('user_department')?> </span>: <?=$doctorinfo->departmentName?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('user_visit_fee')?> </span>: <?=app_currency_format($doctorinfo->visit_fee)?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('user_online_consultation')?> </span>: 
                                                <?=($doctorinfo->online_consultation == 1) ? "<span class='text-success'>".$this->lang->line('user_yes')."</span>" : "<span class='text-success'>".$this->lang->line('user_no')."</span>"?>
                                            </p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('user_consultation_fee')?> </span>: <?=app_currency_format($doctorinfo->consultation_fee)?></p>
                                        </div>
                                    <?php } ?>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('user_status')?> </span>: 
                                            <?=($profile->status == 1) ? "<span class='text-success'>".$this->lang->line('user_active')."</span>" : "<span class='text-success'>".$this->lang->line('user_block')."</span>"?>
                                        </p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('user_role')?> </span>: <?=isset($roles[$profile->roleID]) ? $roles[$profile->roleID] : ''?></p>

                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('user_username')?> </span>: <?=$profile->username?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
                                <div class="userDIV">
                                    <table class="attendance-table">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('user_slno')?></th>
                                                <?php
                                                    for($i=1; $i<=31; $i++) {
                                                       echo  "<th>".$i."</th>";
                                                    }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $presentCount = 0;
                                                $lateexcuseCount = 0;
                                                $lateCount = 0;
                                                $absentCount = 0;

                                                $startyeardate = '01-01-'.date('Y');
                                                $endyeardate   = '01-12-'.date('Y');
                                                $allMonths = get_month_and_year_using_two_date($startyeardate, $endyeardate);

                                                foreach($allMonths as $yearKey => $months) {
                                                    foreach ($months as $month) {
                                                        $monthyear = $month."-".$yearKey;
                                                        if(isset($attendances[$monthyear])) {
                                                            echo "<tr>";
                                                            echo "<td>".ucwords($monthArray[$month])."</td>";
                                                            for ($i=1; $i <= 31; $i++) {    
                                                                $acolumnname = 'a'.$i;
                                                                $textcolorclass = '';
                                                                $val = false;
                                                                if(isset($attendances[$monthyear]) && $attendances[$monthyear]->$acolumnname == 'P') {
                                                                    $presentCount++;
                                                                    $textcolorclass = 'ini-bg-success';
                                                                } elseif(isset($attendances[$monthyear]) && $attendances[$monthyear]->$acolumnname == 'LE') {
                                                                    $lateexcuseCount++;
                                                                    $textcolorclass = 'ini-bg-success';
                                                                } elseif(isset($attendances[$monthyear]) && $attendances[$monthyear]->$acolumnname == 'L') {
                                                                    $lateCount++;
                                                                    $textcolorclass = 'ini-bg-success';
                                                                } elseif(isset($attendances[$monthyear]) && $attendances[$monthyear]->$acolumnname == 'A') {
                                                                    $absentCount++;
                                                                    $textcolorclass = 'ini-bg-danger';
                                                                } elseif((isset($attendances[$monthyear]) && ($attendances[$monthyear]->$acolumnname == NULL || $attendances[$monthyear]->$acolumnname == ''))) {
                                                                    $textcolorclass = 'ini-bg-secondary';
                                                                    $defaultVal = 'N/A';
                                                                    $val = true;
                                                                }

                                                                if($val) {
                                                                    echo "<td class='".$textcolorclass."'>".$defaultVal."</td>";
                                                                } else {
                                                                    echo "<td class='".$textcolorclass."'>".$attendances[$monthyear]->$acolumnname."</td>";
                                                                }
                                                            }
                                                            echo "</tr>";
                                                        } else {
                                                            $monthyear = $month."-".$yearKey;
                                                            echo "<tr>";
                                                            echo "<td>".ucwords($monthArray[$month])."</td>";
                                                            for ($i=1; $i <= 31; $i++) {    
                                                                $textcolorclass = 'ini-bg-secondary';
                                                                echo "<td class='".$textcolorclass."'>".'N/A'."</td>";
                                                            }
                                                            echo "</tr>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="totalattendanceCount">
                                    <?=$this->lang->line('user_total_present')?>:<?=$presentCount?>, 
                                    <?=$this->lang->line('user_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                                    <?=$this->lang->line('user_total_late')?>:<?=$lateCount?>, 
                                    <?=$this->lang->line('user_total_absent')?>:<?=$absentCount?>
                                </div>
                            </div>
                            <?php if(inicompute($managesalary)) { ?>
                                <div class="tab-pane fade" id="salary" role="tabpanel" aria-labelledby="salary-tab">
                                    <?php if($managesalary->salary == '1') { ?>
                                        <div class="row">
                                            <div class="col-sm-6 payroll-margin-bottom">
                                                <div class="info-box">
                                                    <p class="margin">
                                                        <span><?=$this->lang->line("user_salary_grades")?>:&nbsp;</span>
                                                        <?=$salarytemplate->salary_grades?>
                                                    </p>
                                                    <p class="margin">
                                                        <span><?=$this->lang->line("user_basic_salary")?>:&nbsp;</span>
                                                        <?=number_format($salarytemplate->basic_salary, 2)?>
                                                    </p>
                                                    <p class="margin">
                                                        <span><?=$this->lang->line("user_overtime_rate")?>:&nbsp;</span>
                                                        <?=number_format($salarytemplate->overtime_rate, 2)?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="box box-border">
                                                    <div class="box-header box-header-for-payroll">
                                                        <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('user_allowances')?></h3>
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
                                                        <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('user_deductions')?></h3>
                                                    </div><!-- /.box-header -->
                                                    <!-- form start -->
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
                                                        <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('user_total_salary_details')?></h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td class="col-sm-8 td-line-height-for-payroll"><?=$this->lang->line('user_gross_salary')?></td>
                                                                <td class="col-sm-4 td-line-height-for-payroll"><?=number_format($grosssalary, 2)?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="col-sm-8 td-line-height-for-payroll"><?=$this->lang->line('user_total_deduction')?></td>
                                                                <td class="col-sm-4 td-line-height-for-payroll"><?=number_format($totaldeduction, 2)?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="col-sm-8 td-line-height-for-payroll"><?=$this->lang->line('user_net_salary')?></td>
                                                                <td class="col-sm-4 td-line-height-for-payroll"><b><?=number_format($netsalary, 2)?></b></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } elseif($managesalary->salary == 2) { ?>
                                        <div class="row">
                                            <div class="col-sm-6 payroll-margin-bottom">
                                                <div class="info-box">
                                                    <p class="margin">
                                                        <span><?=$this->lang->line("user_salary_grades")?>:&nbsp;</span>
                                                        <?=$hourly_salary->hourly_grades?>
                                                    </p>
                                                    <p class="margin">
                                                        <span><?=$this->lang->line("user_hourly_salary")?>:&nbsp;</span>
                                                        <?=number_format($hourly_salary->hourly_rate, 2)?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8 offset-sm-4">
                                                <div class="box box-border">
                                                    <div class="box-header box-header-for-payroll">
                                                        <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('user_total_salary_details')?></h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td class="col-sm-8 td-line-height-for-payroll"><?=$this->lang->line('user_gross_salary')?></td>
                                                                <td class="col-sm-4 td-line-height-for-payroll"><?=number_format($grosssalary, 2)?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="col-sm-8 td-line-height-for-payroll"><?=$this->lang->line('user_total_deduction')?></td>
                                                                <td class="col-sm-4 td-line-height-for-payroll"><?=number_format($totaldeduction, 2)?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="col-sm-8 td-line-height-for-payroll"><?=$this->lang->line('user_net_salary')?></td>
                                                                <td class="col-sm-4 td-line-height-for-payroll"><b><?=number_format($netsalary, 2)?></b></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                    <div id="hide-table">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?=$this->lang->line('user_slno')?></th>
                                                    <th><?=$this->lang->line('user_month')?></th>
                                                    <th><?=$this->lang->line('user_date')?></th>
                                                    <th><?=($managesalary->salary == 2) ? $this->lang->line('user_net_salary_hourly') : $this->lang->line('user_net_salary');?></th>
                                                    <th><?=$this->lang->line('user_payment_amount')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $paymentTotal = 0; if(inicompute($makepayments)) { $i = 1; foreach($makepayments as $makepayment) { ?>
                                                    <tr>
                                                        <td data-title="<?=$this->lang->line('user_slno')?>">
                                                            <?=$i;?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('user_month')?>">
                                                            <?php echo date("M Y", strtotime('1-'.$makepayment->month)); ?>
                                                        </td>

                                                        <td data-title="<?=$this->lang->line('user_date')?>">
                                                            <?php echo date("d M Y", strtotime($makepayment->create_date)); ?>
                                                        </td>

                                                        <td data-title="<?=($managesalary->salary == 2) ? $this->lang->line('user_net_salary_hourly') : $this->lang->line('user_net_salary'); ?>">
                                                            <?php
                                                                if(isset($makepayment->total_hours)) {
                                                                    echo '('.$makepayment->total_hours. 'X' . $makepayment->net_salary .') = '. (number_format($makepayment->total_hours * $makepayment->net_salary, 2)); 
                                                                } else {
                                                                    echo number_format($makepayment->net_salary, 2); 
                                                                }
                                                            ?>
                                                        </td>

                                                        <td data-title="<?=$this->lang->line('user_payment_amount')?>">
                                                            <?=number_format($makepayment->payment_amount, 2); $paymentTotal += $makepayment->payment_amount; ?>
                                                        </td>
                                                    </tr>
                                                <?php $i++; }} ?>
                                                <tr>
                                                    <td colspan="4" data-title="<?=$this->lang->line('user_total')?>">
                                                        <?php if($generalsettings->currency_code) { echo '<b>'. $this->lang->line('user_total').' ('.$generalsettings->currency_code.')'. '</b>'; } else { echo '<b>'. $this->lang->line('user_total') . '</b>'; }
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('user_total')?> <?=$this->lang->line('user_payment_amount')?>">
                                                        <?=number_format($paymentTotal, 2)?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
                                <div class="document-view-dis">
                                    <?php if(permissionChecker('user_add')) { ?>
                                        <button class="btn btn-primary document" data-toggle="modal" data-target="#documentupload"><i class="fa fa-plus"> </i> <?=$this->lang->line('user_add_document')?></button>
                                    <?php } ?>
                                    <div id="hide-table">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?=$this->lang->line('user_slno')?></th>
                                                    <th><?=$this->lang->line('user_title')?></th>
                                                    <th><?=$this->lang->line('user_date')?></th>
                                                    <?php if((permissionChecker('user_add') && permissionChecker('user_delete')) || ($this->session->userdata('loginuserID') == $profile->userID)) { ?>
                                                        <th><?=$this->lang->line('user_action')?></th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(inicompute($documents)) { $i = 1; foreach ($documents as $document) {  ?>
                                                    <tr>
                                                        <td data-title="<?=$this->lang->line('user_slno')?>">
                                                            <?=$i?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('user_title')?>">
                                                            <?=$document->title?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('user_date')?>">
                                                            <?=date('d M Y', strtotime($document->create_date))?>
                                                        </td>
                                                        <?php if((permissionChecker('user_add') && permissionChecker('user_delete')) || ($this->session->userdata('loginuserID') == $profile->userID)) { ?>
                                                            <td data-title="<?=$this->lang->line('user_action')?>">
                                                                <?php  
                                                                    if((permissionChecker('user_add') && permissionChecker('user_delete')) || ($this->session->userdata('loginuserID') == $profile->userID)) {
                                                                        echo btn_download('user/downloaddocument/'.$document->documentID.'/'.$profile->userID, $this->lang->line('user_download'));
                                                                    }
                                                                    echo "&nbsp;";
                                                                    if(permissionChecker('user_add') && permissionChecker('user_delete')) {
                                                                        echo btn_delete_show('user/deletedocument/'.$document->documentID.'/'.$profile->userID, $this->lang->line('user_delete'));
                                                                    } 
                                                                ?>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php $i++; } } ?>
                                            </tbody>
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

<?php if(permissionChecker('user_add')) { ?>
    <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" id="formData">
        <div class="modal" id="documentupload">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="mdoal-title">               
                            <?=$this->lang->line('user_document_upload')?>
                        </h6>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <input type="hidden" name="userID" value="<?=$profile->userID?>"/>
                        <div class="form-group <?=form_error('document_file') ? 'text-danger' : '' ?>">
                            <label><?=$this->lang->line('user_document_title')?><span class="text-danger"> *</span></label>
                            <input type="text" class="form-control document_title <?=form_error('document_title') ? 'is-invalid' : '' ?>" id="document_title" name="document_title"  value="<?=set_value('document_title')?>">
                            <span class="text-danger" id="error_document_title"></span>
                        </div>
                        <div class="form-group <?=form_error('document_file') ? 'text-danger' : '' ?>">
                            <label><?=$this->lang->line('user_document_file')?> <span class="text-danger"> *</span></label>
                            <div class="custom-file">
                                <input type="file" name="document_file" class="custom-file-input file-upload-input document_file" id="file-upload">
                                <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('user_choose_file')?></label>
                            </div>
                            <span class="text-danger" id="error_document_file"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('user_cancel')?></button>
                        <button type="button" class="btn btn-primary upload_document"><?=$this->lang->line('user_upload')?></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php } ?>

<form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST">
    <div class="modal" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title"><?=$this->lang->line('user_send_pdf_to_mail')?></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <label><?=$this->lang->line('user_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('user_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('user_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('user_send')?></button>
                </div>
            </div>
        </div>
    </div>
</form>