<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('testreport/pdf/'.$testcategoryID.'/'.$testlabelID.'/'.$billID.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('testreport/xlsx/'.$testcategoryID.'/'.$testlabelID.'/'.$billID.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('testreport',$pdf_preview_uri, $this->lang->line('testreport_pdf_preview'));
                echo btn_xlsxReport('testreport', $xlsx_preview_uri, $this->lang->line('testreport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('testreport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
               
                <div class="col-sm-12">
                    <h6 class="pull-left report-pulllabel">
                        <?php $f=TRUE; if($from_date && $to_date) { $f=FALSE;
                            echo $this->lang->line('testreport_from_date')." : ".$label_from_date;
                        } elseif ($testcategoryID) {
                            $f=FALSE;
                            echo $this->lang->line('testreport_category')." : ".$label_testcategory;
                        } elseif ($billID) {
                            $f=FALSE;
                            echo $this->lang->line('testreport_bill_no')." : ".$label_bill_no;
                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($from_date && $to_date) {
                            echo $this->lang->line('testreport_to_date')." : ".$label_to_date;
                        } elseif($testlabelID) {
                            echo $this->lang->line('testreport_test_name')." : ".$label_testlabel;
                        } elseif($from_date) {
                            echo $this->lang->line('testreport_from_date')." : ".$label_from_date;
                        } ?>
                    </h6>
                </div>

                <div class="col-sm-12">
                    <?php  if(inicompute($tests)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
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
                                        <td data-title="#"><?=$i?></td>
                                        <td data-title="<?=$this->lang->line('testreport_test_name')?>"><?=isset($testlabels[$test->testlabelID]) ? $testlabels[$test->testlabelID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('testreport_category')?>"><?=isset($testcategorys[$test->testcategoryID]) ? $testcategorys[$test->testcategoryID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('testreport_bill_no')?>"><?=$test->billID?></td>
                                        <td data-title="<?=$this->lang->line('testreport_name')?>"><?=isset($patients[$test->billID]) ? $patients[$test->billID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('testreport_date')?>"><?=app_date($test->create_date, false)?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('testreport_data_not_found')?></p>
                        </div>
                <?php } ?>
                </div>
                <div class="col-sm-12">
                    <?=reportfooter($generalsettings)?>
                </div>
            </div>
        </div>
    </div>
</section>
