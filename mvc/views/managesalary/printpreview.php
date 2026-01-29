<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="view-main-area-top-left">
                        <img class="view-main-area-top-img" src="<?=pdfimagelink($user->photo,'uploads/user')?>" alt="">
                    </div>
                    <div class="view-main-area-top-right">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('managesalary_name')?></div>
                            <div class="single-user-info-value">: <?=$user->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('managesalary_designation')?></div>
                            <div class="single-user-info-value">: <?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('managesalary_gender')?></div>
                            <div class="single-user-info-value">: <?=($user->gender == 1) ? $this->lang->line('managesalary_male') : $this->lang->line('managesalary_female') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('managesalary_dob')?></div>
                            <div class="single-user-info-value">: <?=app_date($user->dob)?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('managesalary_phone')?></div>
                            <div class="single-user-info-value">: <?=$user->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <?php if($managesalary->salary == '1') { ?>
                        <div class="view-main-area-bottom-full-width">
                            <div class="view-main-area-bottom-half-width">
                                <table class="view-main-bottom-table">
                                    <tr>
                                        <td><?=$this->lang->line("managesalary_salary_grades")?></td>
                                        <td><?=$salarytemplate->salary_grades?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("managesalary_basic_salary")?></td>
                                        <td><?=number_format($salarytemplate->basic_salary, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("managesalary_overtime_rate")?></td>
                                        <td><?=number_format($salarytemplate->overtime_rate, 2)?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="view-main-area-bottom-full-width">
                            <div class="view-main-area-bottom-half-width">
                                <table class="view-main-bottom-table">
                                    <tr>
                                        <td class="font-weight-bold" colspan="2"><?=$this->lang->line('managesalary_allowances')?></td>
                                    </tr>
                                    <?php 
                                        if(inicompute($salaryoptions)) {
                                            foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                if($salaryoption->option_type == 1) { ?>
                                                    <tr>
                                                        <td><?=$salaryoption->label_name?></td>
                                                        <td><?=number_format($salaryoption->label_amount, 2)?></td>
                                                    </tr>
                                    <?php } } } ?>
                                </table>
                            </div>
                            <div class="view-main-area-bottom-half-width">
                                <table class="view-main-bottom-table">
                                    <tr>
                                        <td  class="font-weight-bold" colspan="2"><?=$this->lang->line('managesalary_deductions')?></td>
                                    </tr>
                                    <?php 
                                        if(inicompute($salaryoptions)) {
                                            foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                if($salaryoption->option_type == 2) { ?>
                                                    <tr>
                                                        <td><?=$salaryoption->label_name?></td>
                                                        <td><?=number_format($salaryoption->label_amount, 2)?></td>
                                                    </tr>
                                    <?php } } } ?>
                                </table>
                            </div>
                        </div>
                        <div class="view-main-area-bottom-full-width">
                            <div class="view-main-area-bottom-half-width">
                                &nbsp;
                            </div>
                            <div class="view-main-area-bottom-half-width">
                                <table class="view-main-bottom-table">
                                    <tr>
                                        <td  class="font-weight-bold" colspan="2"><?=$this->lang->line('managesalary_total_salary_details')?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('managesalary_gross_salary')?></td>
                                        <td><?=number_format($grosssalary, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('managesalary_total_deduction')?></td>
                                        <td><?=number_format($totaldeduction, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('managesalary_net_salary')?></td>
                                        <td><?=number_format($netsalary, 2)?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php } elseif($managesalary->salary == 2) { ?>
                        <div class="view-main-area-bottom-full-width">
                            <div class="view-main-area-bottom-half-width">
                                <table class="view-main-bottom-table">
                                    <tr>
                                        <td><?=$this->lang->line("managesalary_salary_grades")?></td>
                                        <td><?=$hourly_salary->hourly_grades?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("managesalary_hourly_rate")?></td>
                                        <td><?=number_format($hourly_salary->hourly_rate, 2)?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="view-main-area-bottom-full-width">
                            <div class="view-main-area-bottom-half-width">
                                &nbsp;
                            </div>
                            <div class="view-main-area-bottom-half-width">
                                <table class="view-main-bottom-table">
                                    <tr>
                                        <td class="font-weight-bold" colspan="2"><?=$this->lang->line('managesalary_total_salary_details')?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('managesalary_gross_salary')?></td>
                                        <td><?=number_format($grosssalary, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('managesalary_total_deduction')?></td>
                                        <td><?=number_format($totaldeduction, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('managesalary_net_salary')?></td>
                                        <td><?=number_format($netsalary, 2)?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>