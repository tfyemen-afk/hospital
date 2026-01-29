<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="view-main-area-top-left">
                        <img class="view-main-area-top-img" src="<?=pdfimagelink($user->photo,'uploads/user')?>" alt="">
                    </div>
                    <div class="view-main-area-top-right">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('attendance_name')?></div>
                            <div class="single-user-info-value">: <?=$user->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('attendance_designation')?></div>
                            <div class="single-user-info-value">: <?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('attendance_gender')?></div>
                            <div class="single-user-info-value">: <?=($user->gender == 1) ? $this->lang->line('attendance_male') : $this->lang->line('attendance_female') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('attendance_dob')?></div>
                            <div class="single-user-info-value">: <?=app_date($user->dob)?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('attendance_phone')?></div>
                            <div class="single-user-info-value">: <?=$user->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <?php
                        $monthArray = array(
                          "01" => "jan",
                          "02" => "feb",
                          "03" => "mar",
                          "04" => "apr",
                          "05" => "may",
                          "06" => "jun",
                          "07" => "jul",
                          "08" => "aug",
                          "09" => "sep",
                          "10" => "oct",
                          "11" => "nov",
                          "12" => "dec"
                        );
                    ?>
                    <h4><?=$this->lang->line("attendance_information")?></h4>
                    <table class="view-main-attendance-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <?php
                                    for($i=1; $i<=31; $i++) {
                                       echo  "<th>".$i."</th>";
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $leavedayCount   = 0;
                                $presentCount    = 0;
                                $lateexcuseCount = 0;
                                $lateCount       = 0;
                                $absentCount     = 0;
                                $startyeardate = '01-01-'.date('Y');
                                $endyeardate   = '01-12-'.date('Y');
                                $allMonths = get_month_and_year_using_two_date($startyeardate, $endyeardate);

                                foreach($allMonths as $yearKey => $months) {
                                    foreach ($months as $month) {
                                        $monthyear = $month."-".$yearKey;
                                        if(isset($attendances[$monthyear])) {
                                            echo "<tr>";
                                                echo "<td>".ucwords($monthArray[$month])."</td>";
                                                for ($i=1; $i <= 31; $i++) {    
                                                    $acolumnname = 'a'.$i;

                                                    $textcolorclass = '';
                                                    $val = false;
                                                    if(isset($attendances[$monthyear]) && $attendances[$monthyear]->$acolumnname == 'P') {
                                                        $presentCount++;
                                                        $textcolorclass = 'ini-bg-success';
                                                    } elseif(isset($attendances[$monthyear]) && $attendances[$monthyear]->$acolumnname == 'LE') {
                                                        $lateexcuseCount++;
                                                        $textcolorclass = 'ini-bg-success';
                                                    } elseif(isset($attendances[$monthyear]) && $attendances[$monthyear]->$acolumnname == 'L') {
                                                        $lateCount++;
                                                        $textcolorclass = 'ini-bg-success';
                                                    } elseif(isset($attendances[$monthyear]) && $attendances[$monthyear]->$acolumnname == 'A') {
                                                        $absentCount++;
                                                        $textcolorclass = 'ini-bg-danger';
                                                    } elseif((isset($attendances[$monthyear]) && ($attendances[$monthyear]->$acolumnname == NULL || $attendances[$monthyear]->$acolumnname == ''))) {
                                                        $textcolorclass = 'ini-bg-secondary';
                                                        $defaultVal = 'N/A';
                                                        $val = true;
                                                    }

                                                    if($val) {
                                                        echo "<td class='".$textcolorclass."'>".$defaultVal."</td>";
                                                    } else {
                                                        echo "<td class='".$textcolorclass."'>".$attendances[$monthyear]->$acolumnname."</td>";
                                                    }
                                                }
                                            echo "</tr>";
                                        } else {
                                            $monthyear = $month."-".$yearKey;
                                            echo "<tr>";
                                                echo "<td>".ucwords($monthArray[$month])."</td>";
                                                for ($i=1; $i <= 31; $i++) {    
                                                    $textcolorclass = 'ini-bg-secondary';
                                                    echo "<td class='".$textcolorclass."'>".'N/A'."</td>";
                                                }
                                            echo "</tr>";
                                        }
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="view-main-attendance-info">
                        <?=$this->lang->line('attendance_total_present')?>:<?=$presentCount?>, 
                        <?=$this->lang->line('attendance_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                        <?=$this->lang->line('attendance_total_late')?>:<?=$lateCount?>, 
                        <?=$this->lang->line('attendance_total_absent')?>:<?=$absentCount?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>