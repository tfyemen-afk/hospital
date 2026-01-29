<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-bottom">
                    <div class="view-main-area-bottom-full-width">
                        <div class="view-main-area-bottom-half-width">
                            <table class="view-main-bottom-table">
                                <tr>
                                    <td><?=$this->lang->line("salarytemplate_salary_grades")?></td>
                                    <td><?=$salarytemplate->salary_grades?></td>
                                </tr>
                                <tr>
                                    <td><?=$this->lang->line("salarytemplate_basic_salary")?></td>
                                    <td><?=number_format($salarytemplate->basic_salary, 2)?></td>
                                </tr>
                                <tr>
                                    <td><?=$this->lang->line("salarytemplate_overtime_rate")?></td>
                                    <td><?=number_format($salarytemplate->overtime_rate, 2)?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="view-main-area-bottom-full-width">
                        <div class="view-main-area-bottom-half-width">
                            <table class="view-main-bottom-table">
                                <tr>
                                    <td class="font-weight-bold" colspan="2"><?=$this->lang->line('salarytemplate_allowances')?></td>
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
                                    <td  class="font-weight-bold" colspan="2"><?=$this->lang->line('salarytemplate_deductions')?></td>
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
                                    <td  class="font-weight-bold" colspan="2"><?=$this->lang->line('salarytemplate_total_salary_details')?></td>
                                </tr>
                                <tr>
                                    <td><?=$this->lang->line('salarytemplate_gross_salary')?></td>
                                    <td><?=number_format($grosssalary, 2)?></td>
                                </tr>
                                <tr>
                                    <td><?=$this->lang->line('salarytemplate_total_deduction')?></td>
                                    <td><?=number_format($totaldeduction, 2)?></td>
                                </tr>
                                <tr>
                                    <td><?=$this->lang->line('salarytemplate_net_salary')?></td>
                                    <td><?=number_format($netsalary, 2)?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>