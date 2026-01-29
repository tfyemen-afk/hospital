<!DOCTYPE html>
<html>
    <head></head>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('incomereport_report_for')?> - <?=$this->lang->line('incomereport_income')?></h3>
        </div>
        <?php if($from_date && $to_date) { ?>
            <div>
                <h6 class="pull-left report-pulllabel">
                    <?=$this->lang->line('incomereport_from_date')?> : <?=date('d M Y',$from_date)?>
                </h6>  
                <h6 class="pull-right report-pulllabel">
                    <?=$this->lang->line('incomereport_to_date')?> : <?=date('d M Y',$to_date)?>
                </h6> 
            </div> 
        <?php } elseif($from_date) { ?>
            <div>
                <h6 class="pull-left report-pulllabel">
                    <?=$this->lang->line('incomereport_from_date')?> : <?=date('d M Y',$from_date)?>
                </h6> 
            </div> 
        <?php } ?>
        <div>
            <?php  if(inicompute($totalincomes)) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?=$this->lang->line('incomereport_name')?></th>
                            <th><?=$this->lang->line('incomereport_type')?></th>
                            <th><?=$this->lang->line('incomereport_date')?></th>
                            <th><?=$this->lang->line('incomereport_amount')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; $total_amount = 0;
                        foreach($totalincomes as $totalincome) { $i++; ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$totalincome['name']?></td>
                            <td><?=$totalincome['type']?></td>
                            <td><?=app_date($totalincome['date'], FALSE)?></td>
                            <td><?=number_format($totalincome['amount'],2)?></td>
                        </tr>
                        <?php $total_amount += $totalincome['amount']; } ?>
                        <tr>
                            <td colspan="4"><?=$this->lang->line('incomereport_grand_total')?> <span class="font-weight-bold"><?=!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : ''?></span></td>
                            <td class="font-weight-bold">
                                <?=number_format($total_amount, 2);?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                    <div class="report-not-found">
                        <p><?=$this->lang->line('incomereport_data_not_found')?></p>
                    </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>