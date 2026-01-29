<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('attendancereport/pdf/'.$attendancetype.'/'.$date);
                $xlsx_preview_uri = site_url('attendancereport/xlsx/'.$attendancetype.'/'.$date);
                echo btn_pdfPreviewReport('attendancereport',$pdf_preview_uri, $this->lang->line('attendancereport_pdf_preview'));
                echo btn_xlsxReport('attendancereport', $xlsx_preview_uri, $this->lang->line('attendancereport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('attendancereport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
                <div class="col-sm-12">
                    <h6 class="pull-left report-pulllabel">
                        <?=$this->lang->line('attendancereport_attendance_type')." : ".$attendance_type?>
                    </h6>
                    <h6 class="pull-right report-pulllabel">
                        <?=$this->lang->line('attendancereport_date')." : ".date('d M Y',$date)?>
                    </h6>
                </div>
                <div class="col-sm-12">
                    <?php  if(inicompute($users)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('attendancereport_photo')?></th>
                                    <th><?=$this->lang->line('attendancereport_name')?></th>
                                    <th><?=$this->lang->line('attendancereport_designation')?></th>
                                    <th><?=$this->lang->line('attendancereport_email')?></th>
                                    <th><?=$this->lang->line('attendancereport_phone')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; $flag = 0; foreach($users as $user) { $i++;

                                    $attendanceDay = isset($attendances[$user->userID]) ? $attendances[$user->userID]->$aday : '';
                                    if($attendancetype == 'P' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                                        continue;
                                    } elseif($attendancetype == 'LE' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='P' || $attendanceDay =='L' )) {
                                        continue;
                                    } elseif($attendancetype == 'L' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='P' )) {
                                        continue;
                                    } elseif($attendancetype == 'A' && ($attendanceDay == 'P' || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                                        continue;
                                    } 
                                    $flag = 1;
                                ?>
                                <tr>
                                    <td data-title="#"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('attendancereport_photo')?>"><img class="img-responsive table-image-size" src="<?=imagelink($user->photo, '/uploads/user/') ?>"/></td>
                                    <td data-title="<?=$this->lang->line('attendancereport_name')?>"><?=$user->name?></td>
                                    <td data-title="<?=$this->lang->line('attendancereport_designation')?>"><?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></td>
                                    <td data-title="<?=$this->lang->line('attendancereport_email')?>"><?=$user->email?></td>
                                    <td data-title="<?=$this->lang->line('attendancereport_phone')?>"><?=$user->phone?></td>
                                </tr>
                                <?php } if(!$flag) { ?>
                                    <tr>
                                        <td data-title="#" colspan="6">
                                            <?=$this->lang->line('attendancereport_data_not_found')?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('attendancereport_data_not_found')?></p>
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