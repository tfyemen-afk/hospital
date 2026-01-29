<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri = site_url('leaveapplicationreport/pdf/'.$roleID.'/'.$userID.'/'.$categoryID.'/'.$statusID.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('leaveapplicationreport/xlsx/'.$roleID.'/'.$userID.'/'.$categoryID.'/'.$statusID.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('leaveapplicationreport',$pdf_preview_uri, $this->lang->line('leaveapplicationreport_pdf_preview'));
                echo btn_xlsxReport('leaveapplicationreport',$xlsx_preview_uri, $this->lang->line('leaveapplicationreport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('leaveapplicationreport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
                <div class="col-sm-12">
                    <?php 
                        $leftlabel  = "";
                        $rightlabel = "";
                        if($from_date && $to_date) { 
                            $leftlabel = $this->lang->line('leaveapplicationreport_from_date')." : ".$label_from_date;;
                        } elseif($categoryID) {
                            $leftlabel = $this->lang->line('leaveapplicationreport_category')." : ".$label_category_name;;
                        } elseif($roleID) {
                            $leftlabel = $this->lang->line('leaveapplicationreport_role')." : ".(isset($roles[$roleID]) ? $roles[$roleID] : '');
                        }

                        if($from_date && $to_date) {
                            $rightlabel = $this->lang->line('leaveapplicationreport_to_date')." : ".$label_to_date;
                        } elseif($statusID) { 
                            $rightlabel = $this->lang->line('leaveapplicationreport_status')." : ".(isset($statusArray[$statusID]) ? $statusArray[$statusID] : '');
                        } elseif((int)$userID) {
                            $rightlabel = $this->lang->line('leaveapplicationreport_user')." : ".(isset($users[$userID]) ? $users[$userID] : '');
                        } elseif($from_date) {
                            $rightlabel = $this->lang->line('leaveapplicationreport_from_date')." : ".$label_from_date;
                        } 
                    ?>
                    <?php $f=TRUE; if($leftlabel) { $f=FALSE; ?>
                        <h6 class="pull-left report-pulllabel"><?=$leftlabel?></h6>
                    <?php } ?>
                    <?php if($rightlabel) { ?>
                        <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel"><?=$rightlabel?></h6>
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                    <?php  if(inicompute($leaveapplications)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('leaveapplicationreport_slno')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_appplicant')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_role')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_category')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_date')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_schedule')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_days')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_status')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; foreach($leaveapplications as $leaveapplication) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_appplicant')?>"><?=isset($users[$leaveapplication->create_userID]) ? $users[$leaveapplication->create_userID] : ''?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_role')?>"><?=isset($roles[$leaveapplication->create_roleID]) ? $roles[$leaveapplication->create_roleID] : ''?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_category')?>"><?=$leaveapplication->leavecategory?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_date')?>"><?=app_date($leaveapplication->create_date)?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_schedule')?>"><?=app_date($leaveapplication->from_date)?> - <?=app_date($leaveapplication->to_date)?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_days')?>"><?=$leaveapplication->leave_days?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_status')?>">
                                        <?php 
                                            if($leaveapplication->status == '1') {
                                                echo $this->lang->line('leaveapplicationreport_approved');
                                            } elseif($leaveapplication->status == '0') {
                                                echo $this->lang->line('leaveapplicationreport_declined');
                                            } else {
                                                echo $this->lang->line('leaveapplicationreport_pending');
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('leaveapplicationreport_data_not_found')?></p>
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
