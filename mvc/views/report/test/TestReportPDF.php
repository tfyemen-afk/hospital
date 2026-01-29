<!DOCTYPE html>
<html>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('testreport_report_for')?> - <?=$this->lang->line('testreport_test')?></h3>
        </div>
        <div>
            <?php 
                $leftlabel  = "";
                $rightlabel = "";
                if($from_date && $to_date) { 
                    $leftlabel =  $this->lang->line('testreport_from_date')." : ".$label_from_date;;
                } elseif($testcategoryID) {
                    $leftlabel =  $this->lang->line('testreport_category')." : ".$label_testcategory;;
                } elseif($billID) {
                    $leftlabel =  $this->lang->line('testreport_bill_no')." : ".$label_bill_no;;
                }

                if($from_date && $to_date) {
                    $rightlabel = $this->lang->line('testreport_to_date')." : ".$label_to_date;
                } elseif($testlabelID) {
                    $rightlabel = $this->lang->line('testreport_test_name')." : ".$label_testlabel;
                } elseif($from_date) {
                    $rightlabel = $this->lang->line('testreport_from_date')." : ".$label_from_date;
                } 
            ?>
            <?php $f=TRUE; if($leftlabel) { $f=FALSE; ?>
                <h6 class="pull-left report-pulllabel"><?=$leftlabel?></h6>
            <?php } ?>

            <?php if($rightlabel) { ?>
                <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel"><?=$rightlabel?></h6>
            <?php } ?>
        </div>
        <div>
            <?php if(inicompute($tests)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?=$this->lang->line('testreport_test_name')?></th>
                        <th><?=$this->lang->line('testreport_category')?></th>
                        <th><?=$this->lang->line('testreport_bill_no')?></th>
                        <th><?=$this->lang->line('testreport_name')?></th>
                        <th><?=$this->lang->line('testreport_date')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 0; 
                        foreach($tests as $test) { $i++; ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=isset($testlabels[$test->testlabelID]) ? $testlabels[$test->testlabelID] : ''?></td>
                            <td><?=isset($testcategorys[$test->testcategoryID]) ? $testcategorys[$test->testcategoryID] : ''?></td>
                            <td><?=$test->billID?></td>
                            <td><?=isset($patients[$test->billID]) ? $patients[$test->billID] : ''?></td>
                            <td><?=app_date($test->create_date, false)?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('testreport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>