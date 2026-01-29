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
                            <div class="single-user-info-label"><?=$this->lang->line('makepayment_name')?></div>
                            <div class="single-user-info-value">: <?=$user->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('makepayment_designation')?></div>
                            <div class="single-user-info-value">: <?=inicompute($designation) ? $designation->designation : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('makepayment_gender')?></div>
                            <div class="single-user-info-value">: <?=($user->gender == 1) ? $this->lang->line('makepayment_male') : $this->lang->line('makepayment_female') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('makepayment_dob')?></div>
                            <div class="single-user-info-value">: <?=app_date($user->dob)?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('makepayment_phone')?></div>
                            <div class="single-user-info-value">: <?=$user->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <?php if($makepayment->salaryID == 1) { ?>
                        <div class="view-main-area-bottom-full-width">
                            <div class="view-main-area-bottom-half-width">
                                <table class="view-main-bottom-table">
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_salary_grades")?></td>
                                        <td><?=$salarytemplate->salary_grades?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_basic_salary")?></td>
                                        <td><?=number_format($salarytemplate->basic_salary, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_overtime_rate")?></td>
                                        <td><?=number_format($salarytemplate->overtime_rate, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_month")?></td>
                                        <td><?=date('M Y', strtotime('01-'.$makepayment->month))?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_date")?></td>
                                        <td><?=app_date($makepayment->create_date)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_payment_method")?></td>
                                        <td><?=($makepayment->payment_method == 1) ? $this->lang->line('makepayment_cash') : $this->lang->line('makepayment_cheque')?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_comments")?></td>
                                        <td><?=$makepayment->comments?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="view-main-area-bottom-full-width">
                            <div class="view-main-area-bottom-half-width">
                                <table class="view-main-bottom-table">
                                    <tr>
                                        <td class="font-weight-bold" colspan="2"><?=$this->lang->line('makepayment_allowances')?></td>
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
                                        <td  class="font-weight-bold" colspan="2"><?=$this->lang->line('makepayment_deductions')?></td>
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
                                        <td  class="font-weight-bold" colspan="2"><?=$this->lang->line('makepayment_total_salary_details')?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('makepayment_gross_salary')?></td>
                                        <td><?=number_format($grosssalary, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('makepayment_total_deduction')?></td>
                                        <td><?=number_format($totaldeduction, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('makepayment_net_salary')?></td>
                                        <td><?=number_format($netsalary, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold"><?=$this->lang->line('makepayment_payment_amount')?></td>
                                        <td class="font-weight-bold"><?=number_format($makepayment->payment_amount, 2)?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php } elseif($makepayment->salaryID == 2) { ?>
                        <div class="view-main-area-bottom-full-width">
                            <div class="view-main-area-bottom-half-width">
                                <table class="view-main-bottom-table">
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_salary_grades")?></td>
                                        <td><?=$hourly_salary->hourly_grades?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_hourly_salary")?></td>
                                        <td><?=number_format($hourly_salary->hourly_rate, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_month")?></td>
                                        <td><?=date('M Y', strtotime('01-'.$makepayment->month))?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_date")?></td>
                                        <td><?=app_date($makepayment->create_date)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_payment_method")?></td>
                                        <td><?=($makepayment->payment_method == 1) ? $this->lang->line('makepayment_cash') : $this->lang->line('makepayment_cheque')?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line("makepayment_comments")?></td>
                                        <td><?=$makepayment->comments?></td>
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
                                        <td class="font-weight-bold" colspan="2"><?=$this->lang->line('makepayment_total_salary_details')?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('makepayment_gross_salary')?></td>
                                        <td><?=number_format($grosssalary, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('makepayment_total_deduction')?></td>
                                        <td><?=number_format($totaldeduction, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td><?=$this->lang->line('makepayment_total_hours')?></td>
                                        <td><?=number_format($makepayment->total_hours,2)?></td>
                                    </tr>
                                    <tr>
                                        <?php
                                            $netsalary_label = $makepayment->total_hours .'*'. $makepayment->net_salary;
                                            $netsalary_val   = $makepayment->total_hours * $makepayment->net_salary;
                                        ?>
                                        <td>
                                            <?=$this->lang->line('makepayment_net_salary')?> 
                                            <span class="text-danger">( <?=$netsalary_label?> )</span>
                                        </td>
                                        <td><?=number_format($netsalary_val, 2)?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold"><?=$this->lang->line('makepayment_payment_amount')?></td>
                                        <td class="font-weight-bold"><?=number_format($makepayment->payment_amount, 2)?></td>
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