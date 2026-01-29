<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('salarytemplate_slno')?></th>
                <th><?=$this->lang->line('salarytemplate_salary_grades')?></th>
                <th><?=$this->lang->line('salarytemplate_basic_salary')?></th>
                <th><?=$this->lang->line('salarytemplate_overtime_rate_not_hour')?></th>
                <?php if(permissionChecker('salarytemplate_edit') || permissionChecker('salarytemplate_delete') || permissionChecker('salarytemplate_view')) { ?>
                    <th><?=$this->lang->line('salarytemplate_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if(inicompute($salarytemplates)) {$i = 1; foreach($salarytemplates as $salarytemplate) { ?>
                <tr>
                    <td data-title="<?=$this->lang->line('salarytemplate_slno')?>">
                        <?php echo $i; ?>
                    </td>
                    <td data-title="<?=$this->lang->line('salarytemplate_salary_grades')?>">
                        <?=$salarytemplate->salary_grades?>
                    </td>
                    <td data-title="<?=$this->lang->line('salarytemplate_basic_salary')?>">
                        <?=number_format($salarytemplate->basic_salary, 2)?>
                    </td>
                    <td data-title="<?=$this->lang->line('salarytemplate_overtime_rate_not_hour')?>">
                        <?=number_format($salarytemplate->overtime_rate, 2)?>
                    </td>
                    <?php if(permissionChecker('salarytemplate_edit') || permissionChecker('salarytemplate_delete') || permissionChecker('salarytemplate_view')) { ?>
                        <td data-title="<?=$this->lang->line('salarytemplate_action')?>">
                            <?=btn_view('salarytemplate/view/'.$salarytemplate->salarytemplateID, $this->lang->line('salarytemplate_view'))?>
                            <?=btn_edit('salarytemplate/edit/'.$salarytemplate->salarytemplateID, $this->lang->line('salarytemplate_edit'))?>
                            <?=btn_delete('salarytemplate/delete/'.$salarytemplate->salarytemplateID, $this->lang->line('salarytemplate_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php $i++; }} ?>
        </tbody>
    </table>
</div>