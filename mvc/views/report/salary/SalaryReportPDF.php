<!DOCTYPE html>
<html>
    <head></head>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('salaryreport_report_for')?> - <?=$this->lang->line('salaryreport_salary')?></h3>
        </div>
        <div>
            <h6 class="pull-left report-pulllabel">
                <?php if($from_date && $to_date) { ?>
                    <?=$this->lang->line('salaryreport_from_date')?> : <?=date('d M Y',$from_date)?>
                <?php } elseif($roleID) { ?>
                    <?=$this->lang->line('salaryreport_role')?> : <?=isset($roles[$roleID]) ? $roles[$roleID] : ''?>
                <?php } ?>
            </h6>  
            <h6 class="pull-right report-pulllabel">
                <?php if($from_date && $to_date) { ?>
                    <?=$this->lang->line('salaryreport_to_date')?> : <?=date('d M Y',$to_date)?>
                <?php } elseif((int)$userID) { ?>
                    <?=$this->lang->line('salaryreport_employee')?> : <?=isset($users[$userID]) ? $users[$userID] : '' ?>
                <?php } ?>
            </h6>  
        </div>
        <div>
            <?php  if(inicompute($salarys)) { ?>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?=$this->lang->line('salaryreport_slno')?></th>
                        <th><?=$this->lang->line('salaryreport_date')?></th>
                        <th><?=$this->lang->line('salaryreport_name')?></th>
                        <th><?=$this->lang->line('salaryreport_role')?></th>
                        <th><?=$this->lang->line('salaryreport_month')?></th>
                        <th><?=$this->lang->line('salaryreport_amount')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; foreach($salarys as $salary) { $i++; ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=app_date($salary->create_date)?></td>
                        <td><?=isset($users[$salary->userID]) ? $users[$salary->userID] : ''?></td>
                        <td><?=isset($roles[$salary->roleID]) ? $roles[$salary->roleID] : ''?></td>
                        <td><?=date('M Y', strtotime('01-'.$salary->month))?></td>
                        <td><?=number_format($salary->payment_amount,2)?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <div class="report-notfound">
                <?=$this->lang->line('salaryreport_data_not_found')?>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>