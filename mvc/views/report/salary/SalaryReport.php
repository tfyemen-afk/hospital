<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('salaryreport/pdf/'.$roleID.'/'.$userID.'/'.$month.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('salaryreport/xlsx/'.$roleID.'/'.$userID.'/'.$month.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('salaryreport',$pdf_preview_uri, $this->lang->line('salaryreport_pdf_preview'));
                echo btn_xlsxReport('salaryreport', $xlsx_preview_uri, $this->lang->line('salaryreport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('salaryreport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
                <div class="col-sm-12">
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
                <div class="col-sm-12">
                    <?php  if(inicompute($salarys)) { ?>
                    <div id="hide-table">
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
                                    <td data-title="<?=$this->lang->line('salaryreport_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('salaryreport_date')?>"><?=app_date($salary->create_date)?></td>
                                    <td data-title="<?=$this->lang->line('salaryreport_name')?>"><?=isset($users[$salary->userID]) ? $users[$salary->userID] : ''?></td>
                                    <td data-title="<?=$this->lang->line('salaryreport_role')?>"><?=isset($roles[$salary->roleID]) ? $roles[$salary->roleID] : ''?></td>
                                    <td data-title="<?=$this->lang->line('salaryreport_month')?>"><?=date('M Y', strtotime('01-'.$salary->month))?></td>
                                    <td data-title="<?=$this->lang->line('salaryreport_amount')?>"><?=number_format($salary->payment_amount,2)?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('salaryreport_data_not_found')?></p>
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