<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('incomereport/pdf/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('incomereport/xlsx/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('incomereport',$pdf_preview_uri, $this->lang->line('incomereport_pdf_preview'));
                echo btn_xlsxReport('incomereport', $xlsx_preview_uri, $this->lang->line('incomereport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('incomereport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
                <?php if($from_date && $to_date) { ?>
                    <div class="col-sm-12">
                        <h6 class="pull-left report-pulllabel">
                            <?=$this->lang->line('incomereport_from_date')?> : <?=date('d M Y',$from_date)?>
                        </h6>  
                        <h6 class="pull-right report-pulllabel">
                            <?=$this->lang->line('incomereport_to_date')?> : <?=date('d M Y',$to_date)?>
                        </h6>  
                    </div>
                <?php } elseif($from_date) { ?>
                    <div class="col-sm-12">
                        <h6 class="pull-left report-pulllabel">
                            <?=$this->lang->line('incomereport_from_date')?> : <?=date('d M Y',$from_date)?>
                        </h6>
                    </div>
                <?php } ?>
                <div class="col-sm-12">
                    <?php  if(inicompute($totalincomes)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
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
                                <?php 
                                $i = 0; 
                                $total_amount = 0;
                                foreach($totalincomes as $totalincome) { $i++; ?>
                                <tr>
                                    <td data-title="#"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('incomereport_name')?>"><?=$totalincome['name']?></td>
                                    <td data-title="<?=$this->lang->line('incomereport_name')?>"><?=$totalincome['type']?></td>
                                    <td data-title="<?=$this->lang->line('incomereport_date')?>"><?=app_date($totalincome['date'], FALSE)?></td>
                                    <td data-title="<?=$this->lang->line('incomereport_amount')?>"><?=number_format($totalincome['amount'],2)?></td>
                                </tr>
                                <?php $total_amount += $totalincome['amount']; } ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('incomereport_grand_total')?>" colspan="4"><?=$this->lang->line('incomereport_grand_total')?> <span class="font-weight-bold"><?=!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : ''?></span></td>
                                    <td data-title="<?=$this->lang->line('incomereport_total_amount')?>" class="font-weight-bold">
                                        <?=number_format($total_amount, 2);?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('incomereport_data_not_found')?></p>
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
