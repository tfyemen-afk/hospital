<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('attendanceoverviewreport/pdf/'.$roleID.'/'.$userID.'/'.$month);
                $xlsx_preview_uri = site_url('attendanceoverviewreport/xlsx/'.$roleID.'/'.$userID.'/'.$month);
                echo btn_pdfPreviewReport('attendanceoverviewreport',$pdf_preview_uri, $this->lang->line('attendanceoverviewreport_pdf_preview'));
                echo btn_xlsxReport('attendanceoverviewreport', $xlsx_preview_uri, $this->lang->line('attendanceoverviewreport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('attendanceoverviewreport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
                <div class="col-sm-12">
                    <h6 class="pull-left report-pulllabel">
                        <?php 
                            if($userID) {
                                echo $label_user;
                            } else {
                                echo $label_role;
                            }
                        ?>
                    </h6>
                    <h6 class="pull-right report-pulllabel">
                        <?=$label_month?>
                    </h6>
                </div>
                <div class="col-sm-12">
                    <?php if(inicompute($users)) {
                        $getDayOfMonth = date('t', mktime(0, 0, 0, $monthday[0], 1, $monthday[1]));  ?>
                        <div id="hide-table">
                            <table class="attendance-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?=$this->lang->line('attendanceoverviewreport_name').' / '.$this->lang->line('attendanceoverviewreport_date')?></th>
                                        <?php for($i=1; $i <= $getDayOfMonth; $i++) { ?>
                                            <th><?=$this->lang->line('attendanceoverviewreport_'."$i")?></th>
                                        <?php } ?>
                                        <th><?=$this->lang->line('attendanceoverviewreport_la')?></th>
                                        <th><?=$this->lang->line('attendanceoverviewreport_p')?></th>
                                        <th><?=$this->lang->line('attendanceoverviewreport_le')?></th>
                                        <th><?=$this->lang->line('attendanceoverviewreport_l')?></th>
                                        <th><?=$this->lang->line('attendanceoverviewreport_a')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0; foreach($users  as $user) { 
                                    $leavedayCount   = 0;
                                    $presentCount    = 0;
                                    $lateexcuseCount = 0;
                                    $lateCount       = 0;
                                    $absentCount     = 0;?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_slno')?>"><?=++$i?></td>
                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_date').' / '.$this->lang->line('attendanceoverviewreport_name')?>">
                                            <?=$user->name?>
                                        </td>
                                        <?php
                                            $userleaveapplications = isset($leaveapplications[$user->userID]) ? $leaveapplications[$user->userID] : [];
                                            if(isset($attendances[$user->userID])) {
                                                for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                                    $currentDate = sprintf("%02d", $j).'-'.$monthdays; ?>
                                                    <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
                                                        <?php   
                                                            if(in_array($currentDate, $userleaveapplications)) {
                                                                echo "<span class='ini-text-present'>LA</span>";
                                                                $leavedayCount++;
                                                            } else {
                                                                if($attendances[$user->userID]->$atten == NULL) { 
                                                                    echo "<span class='ini-text-not-assign'>N/A</span>";
                                                                } elseif($attendances[$user->userID]->$atten == 'P') {
                                                                    echo "<span class='ini-text-present'>".$attendances[$user->userID]->$atten."</span>";
                                                                    $presentCount++;
                                                                } elseif($attendances[$user->userID]->$atten == 'LE') {
                                                                    echo "<span class='ini-text-lateex'>".$attendances[$user->userID]->$atten."</span>";
                                                                    $lateexcuseCount++;
                                                                } elseif($attendances[$user->userID]->$atten == 'L') {
                                                                    echo "<span class='ini-text-late'>".$attendances[$user->userID]->$atten."</span>";
                                                                    $lateCount++;
                                                                } elseif($attendances[$user->userID]->$atten == 'A') {
                                                                    echo "<span class='ini-text-absent'>".$attendances[$user->userID]->$atten."</span>";
                                                                    $absentCount++;
                                                                }
                                                            }
                                                        ?>
                                                    </td>
                                                <?php }
                                            } else {
                                                for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                                    $currentDate = sprintf("%02d", $j).'-'.$monthdays; ?>
                                                    <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
                                                        <?php 
                                                            if(in_array($currentDate, $userleaveapplications)) {
                                                                echo "<span class='ini-text-present'>LA</span>";
                                                                $leavedayCount++;
                                                            } else {
                                                                echo "<span class='ini-text-not-assign'>N/A</span>";
                                                            }
                                                        ?>
                                                    </td>
                                            <?php } } ?>
                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_la')?>"><span class="ini-text-weekenday"><?=$leavedayCount?></span></td>
                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_p')?>"><span class="ini-text-present"><?=$presentCount?></span></td>
                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_le')?>"><span class="ini-text-lateex"><?=$lateexcuseCount?></span></td>
                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_l')?>"><span class="ini-text-late"><?=$lateCount?></span></td>
                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_a')?>"><span class="ini-text-absent"><?=$absentCount?></span></td>
                                    </tr>
                                <?php } ?>         
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                    <div class="report-not-found">
                        <p><?=$this->lang->line('attendanceoverviewreport_data_not_found')?></p>
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