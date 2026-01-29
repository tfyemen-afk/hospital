<div class="card">
    <form class="form-horizontal" role="form" method="post" id="templateDataForm">
        <div class="row">
            <div class="col-sm-12 col-margin-bottom">
                <div class='row form-group <?=form_error('salary_grades')?'text-danger' : ''?>'>
                    <div class="col-sm-3">
                        <label for="salary_grades" class="control-label" >
                            <?=$this->lang->line("salarytemplate_salary_grades")?> <span class="text-danger">*</span>
                        </label>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="salary_grades" name="salary_grades" value="<?=set_value('salary_grades',$salarytemplate->salary_grades)?>">
                    </div>
                    <div class="col-sm-4 control-label" id="salary_grades_error" >
                        <?php echo form_error('salary_grades'); ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-margin-bottom">
                <div class='row form-group <?=form_error('basic_salary')?'text-danger' : ''?>'>
                    <div class="col-sm-3">
                        <label for="basic_salary" class="control-label" >
                            <?=$this->lang->line("salarytemplate_basic_salary")?> <span class="text-danger">*</span>
                        </label>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="basic_salary" name="basic_salary" value="<?=set_value('basic_salary', $salarytemplate->basic_salary)?>">
                    </div>
                    <div class="col-sm-4 control-label" id="basic_salary_error" >
                        <?php echo form_error('basic_salary'); ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-margin-bottom">
                <div class='row form-group <?=form_error('overtime_rate')?'text-danger' : ''?>'>
                    <div class="col-sm-3">
                        <label for="overtime_rate" class="control-label" >
                            <?=$this->lang->line("salarytemplate_overtime_rate")?> <span class="text-danger">*</span>
                        </label>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="overtime_rate" name="overtime_rate" value="<?=set_value('overtime_rate', $salarytemplate->overtime_rate)?>">
                    </div>
                    <div class="col-sm-4 control-label" id="overtime_rate_error" >
                        <?php echo form_error('overtime_rate'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row col-margin-bottom">
            <div class="col-sm-6">
                <div class="box box-border">
                    <div class="box-header box-header-for-payroll">
                        <p class="box-title box-title-for-payroll"><?=$this->lang->line('salarytemplate_allowances')?></p>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12" id="allowances">
                                <?php $i = 1; if(inicompute($grosssalarylist)) {
                                    $grosssalarylistcount = inicompute($grosssalarylist);
                                    foreach ($grosssalarylist as $grosssalarylistKey => $grosssalarylistValue) { ?>
                                        <div class='row form-group allowancesfield'>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" id="<?php echo 'allowanceslabel'.$i; ?>" name="<?='allowanceslabel'.$i?>" value="<?=$grosssalarylistKey?>" placeholder="<?=$this->lang->line("salarytemplate_allowances_label")?>">
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control allowancesamount" id="<?='allowancesamount'.$i?>" name="<?='allowancesamount'.$i?>" value="<?=$grosssalarylistValue?>" placeholder="<?=$this->lang->line("salarytemplate_allowances_value")?>">
                                            </div>
                                            <div class="col-sm-2" >
                                                <?php if($grosssalarylistcount == 1) { ?>
                                                    <button type="button" class="btn btn-success btn-xs salary-btn salary-btn-allowances-add" id="salary-btn-allowances-add" onclick="addAllowances()">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                <?php } elseif($grosssalarylistcount == $i && $grosssalarylistcount != 1) { ?>
                                                    <button id="<?=$i?>" class="btn btn-danger btn-xs salary-btn salary-btn-allowances-remove" type="button" onclick="removeAllowances(this)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-xs salary-btn salary-btn-allowances-add" id="salary-btn-allowances-add" onclick="addAllowances()">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                <?php } else { ?>
                                                    <button id="<?=$i?>" class="btn btn-danger btn-xs salary-btn salary-btn-allowances-remove" type="button" onclick="removeAllowances(this)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                            <span class="col-sm-12 errorpointallowances" id="<?='allowanceserror'.$i?>">
                                                <?php echo form_error('amount'.$i); ?>
                                            </span>
                                        </div>
                                        <?php $i++; ?>
                                    <?php } } else { ?>
                                    <div class='row form-group allowancesfield'>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="allowanceslabel1" name="allowanceslabel1" value="House Rent" placeholder="<?=$this->lang->line("salarytemplate_allowances_label")?>">
                                        </div>

                                        <div class="col-sm-5">
                                            <input type="text" class="form-control allowancesamount" id="allowancesamount1" name="allowancesamount1" value="" placeholder="<?=$this->lang->line("salarytemplate_allowances_value")?>">
                                        </div>

                                        <div class="col-sm-2" >
                                            <button type="button" class="btn btn-success btn-xs salary-btn salary-btn-allowances-add" id="salary-btn-allowances-add" onclick="addAllowances()">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                        <span class="col-sm-12 errorpointallowances" id="allowanceserror1">
                                            <?php echo form_error('amount1'); ?>
                                        </span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="box box-border">
                    <div class="box-header box-header-for-payroll">
                        <p class="box-title box-title-for-payroll"><?=$this->lang->line('salarytemplate_deductions')?></p>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12" id="deductions">
                                <?php
                                    $j = 1;
                                    if(inicompute($totaldeductionlist)) {
                                        $totaldeductionlistcount = inicompute($totaldeductionlist);
                                        foreach ($totaldeductionlist as $totaldeductionlistKey => $totaldeductionlistValue) {
                                ?>

                                <div class='row form-group deductionsfield'>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="<?='deductionslabel'.$j?>" name="<?='deductionslabel'.$j?>" value="<?=$totaldeductionlistKey?>" placeholder="<?=$this->lang->line("salarytemplate_deductions_label")?>">
                                    </div>

                                    <div class="col-sm-5">
                                        <input type="text" class="form-control deductionsamount" id="<?='deductionsamount'.$j?>" name="<?='deductionsamount'.$j?>" value="<?=$totaldeductionlistValue?>" placeholder="<?=$this->lang->line("salarytemplate_deductions_value")?>">
                                    </div>

                                    <div class="col-sm-2" >
                                        <?php if($totaldeductionlistcount == 1) { ?>
                                            <button type="button" class="btn btn-success btn-xs salary-btn salary-btn-deductions-add" id="salary-btn-deductions-add" onclick="addDeductions()">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        <?php } elseif($totaldeductionlistcount == $j && $totaldeductionlistcount != 1) { ?>
                                            <button id="<?=$j?>" type="button" class="btn btn-danger btn-xs salary-btn salary-btn-deductions-remove" onclick="removeDeductions(this)">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <button type="button" class="btn btn-success btn-xs salary-btn salary-btn-deductions-add" id="salary-btn-deductions-add" onclick="addDeductions()">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        <?php } else { ?>
                                            <button id="<?=$j?>" type="button" class="btn btn-danger btn-xs salary-btn salary-btn-deductions-remove" onclick="removeDeductions(this)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        <?php } ?>
                                    </div>
                                    <span class="col-sm-12 errorpointdeductions" id="<?='deductionserror'.$j?>">
                                        <?php echo form_error('amount'.$j); ?>
                                    </span>
                                </div>
                                <?php 
                                    $j++;                                       
                                        }                                                
                                    } else {
                                ?>
                                    <div class='row form-group deductionsfield'>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="deductionslabel1" name="deductionslabel1" value="Provident Fund" placeholder="<?=$this->lang->line("salarytemplate_deductions_label")?>">
                                        </div>

                                        <div class="col-sm-5">
                                            <input type="text" class="form-control deductionsamount" id="deductionsamount1" name="deductionsamount1" value="" placeholder="<?=$this->lang->line("salarytemplate_deductions_value")?>">
                                        </div>

                                        <div class="col-sm-2" >
                                            <button type="button" class="btn btn-success btn-xs salary-btn salary-btn-deductions-add" id="salary-btn-deductions-add" onclick="addDeductions()">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>

                                        <span class="col-sm-12 errorpointdeductions" id="deductionserror1">
                                            <?php echo form_error('amount1'); ?>
                                        </span>
                                    </div>
                                <?php } ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row col-margin-bottom">
            <div class="col-sm-12">
                <div class="col-sm-8 pull-right">
                    <div class="box box-border">
                        <div class="box-header box-header-for-payroll">
                            <h3 class="box-title box-title-for-payroll"><?=$this->lang->line('salarytemplate_total_salary_details')?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="line-height"><?=$this->lang->line('salarytemplate_gross_salary')?></td>

                                            <td class=""><input class="form-control" id="gross_salary" type="text" value="<?=$grosssalary?>" disabled="disabled" name="gross_salary"></td>
                                        </tr>

                                        <tr>
                                            <td class="line-height"><?=$this->lang->line('salarytemplate_total_deduction')?></td>

                                            <td class=""><input class="form-control" id="total_deduction" type="text" value="<?=$totaldeduction?>" disabled="disabled" name="total_deduction"></td>
                                        </tr>

                                        <tr>
                                            <td class="line-height"><?=$this->lang->line('salarytemplate_net_salary')?></td>

                                            <td class=""><input class="form-control" id="net_salary" type="text" value="<?=$netsalary?>" disabled="disabled" name="net_salary"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xs-12">
            <input class="btn btn-success pull-right col-sm-3 col-xs-12 " type="button" id="addSalaryTemplate" value="<?=$this->lang->line('salarytemplate_update')?>">
        </div>         
    </form>
</div>