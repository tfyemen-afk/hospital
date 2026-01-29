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
<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('attendance_print'))?>
                <?=btn_sm_pdf('attendance/printpreview/'.$user->userID, $this->lang->line('attendance_pdf_preview'))?>
                <?=btn_sm_mail($this->lang->line('attendance_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item"><a href="<?=site_url('attendance/index')?>"><?=$this->lang->line('menu_attendance')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('attendance_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-md-3 user-profile-box">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img src="<?=pdfimagelink($user->photo,'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">           
                            <h3 class="profile-username text-center"><?=$user->name?></h3>
                            <p class="text-muted text-center"><?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('attendance_gender')?></b> <a class="pull-right"><?=($user->gender == '1')? $this->lang->line('attendance_male'): $this->lang->line('attendance_female') ?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('attendance_dob')?></b> <a class="pull-right"><?=app_date($user->dob)?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('attendance_phone')?></b> <a class="pull-right"><?=$user->phone?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 user-profile-details">
                    <ul class="nav nav-tabs bg-color">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#attendance_tab" role="tab" aria-controls="attendance_tab" aria-selected="true"><?=$this->lang->line('attendance_attendance')?></a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active bg-color" id="attendance_tab" role="tabpanel" aria-labelledby="attendance_tab">
                            <div class="scrollDiv">
                                <table class="attendance-table">
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
                                            $leavedayCount = 0;
                                            $presentCount = 0;
                                            $lateexcuseCount = 0;
                                            $lateCount = 0;
                                            $absentCount = 0;

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
                            </div>
                            <div>
                                <p class="totalattendanceCount">
                                    <?=$this->lang->line('attendance_total_present')?>:<?=$presentCount?>, 
                                    <?=$this->lang->line('attendance_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                                    <?=$this->lang->line('attendance_total_late')?>:<?=$lateCount?>, 
                                    <?=$this->lang->line('attendance_total_absent')?>:<?=$absentCount?>
                                </p>
                            </div>
                               
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</article>

<form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST">
    <div class="modal" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title"><?=$this->lang->line('attendance_send_pdf_to_mail')?></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('attendance_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('attendance_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('attendance_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('attendance_send')?></button>
                </div>
            </div>
        </div>
    </div>
</form>