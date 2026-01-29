<article class="content">
    <?php if(config_item('demo')) { ?>
        <section class="section">
            <div class="row">
                <div class="col-sm-12" id="resetDummyData">
                    <div class="callout callout-danger">
                        <h4>Reminder!</h4>
                        <p>Dummy data will be reset in every <code>30</code> minutes</p>
                    </div>
                </div>
            </div>
        </section>

        <script type="text/javascript">
          $(document).ready(function() {
            'use strict';
            let count = 7;
            let countdown = setInterval(function(){
              $("p.countdown").html(count + " seconds remaining!");
              if (count === 0) {
                clearInterval(countdown);
                $('#resetDummyData').hide();
              }
              count--;
            }, 1000);
          });
        </script>
    <?php } ?>

    <?php if((config_item('demo') === FALSE) && ($generalsettings->auto_update_notification == 1) && ($versionChecking != 'none')) { ?>
        <?php if($this->session->userdata('updatestatus') === null) { ?>
            <section class="section">
                <div class="row sameheight-container">
                    <div class="col-md-12" id="updatenotify">
                        <div class="callout callout-success">
                            <h4>Dear Admin</h4>
                            <p>Trust hospital management system has released a new update.</p>
                            <p>Do you want to update it now <?=config_item('iniversion')?> to <?=$versionChecking?> ?</p>
                            <a href="<?=site_url('dashboard/remind')?>" class="btn btn-danger">Remind me</a>
                            <a href="<?=site_url('dashboard/update')?>" class="btn btn-success">Update</a>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
    <?php } ?>

    <section class="section">
        <?php
            $arrayColor = array(
                'bg-orange-dark',
                'bg-teal-light',
                'bg-pink-light',
                'bg-purple-light'
            );
            $allWidgetArray    = $dashboardwidget;

            $widgetArray = array(
                1 => array(
                    'appointment'   => $dashboardwidget['appointment'],
                    'admission'     => $dashboardwidget['admission'],
                    'patient'       => $dashboardwidget['patient'],
                    'user'          => $dashboardwidget['user'],
                ),
                2 => array(
                    'appointment'   => $dashboardwidget['appointment'],
                    'admission'     => $dashboardwidget['admission'],
                    'patient'       => $dashboardwidget['patient'],
                    'prescription'  => $dashboardwidget['prescription'],
                ),
                3 => array(
                    'appointment'   => $dashboardwidget['appointment'],
                    'prescription'  => $dashboardwidget['prescription'],
                    'bill'          => $dashboardwidget['bill'],
                    'billpayment'   => $dashboardwidget['billpayment'],
                ),
                4 => array(
                    'user'          => $dashboardwidget['user'],
                    'income'        => $dashboardwidget['income'],
                    'bill'          => $dashboardwidget['bill'],
                    'billpayment'   => $dashboardwidget['billpayment'],
                ),
                5 => array(
                    'appointment'   => $dashboardwidget['appointment'],
                    'patient'       => $dashboardwidget['patient'],
                    'bill'          => $dashboardwidget['bill'],
                    'billpayment'   => $dashboardwidget['billpayment'],
                ),
                6 => array(
                    'medicine'          => $dashboardwidget['medicine'],
                    'medicinepurchase'  => $dashboardwidget['medicinepurchase'],
                    'medicinesale'      => $dashboardwidget['medicinesale'],
                    'bill'              => $dashboardwidget['bill'],
                ),
                7 => array(
                    'patient'           => $dashboardwidget['patient'],
                    'testcategory'      => $dashboardwidget['testcategory'],
                    'testlabel'         => $dashboardwidget['testlabel'],
                    'test'              => $dashboardwidget['test'],
                ),
                8 => array(
                    'patient'           => $dashboardwidget['patient'],
                    'testcategory'      => $dashboardwidget['testcategory'],
                    'testlabel'         => $dashboardwidget['testlabel'],
                    'test'              => $dashboardwidget['test'],
                ),
                9 => array(
                    'appointment'       => $dashboardwidget['appointment'],
                    'admission'         => $dashboardwidget['admission'],
                    'physicalcondition' => $dashboardwidget['physicalcondition'],
                    'patient'           => $dashboardwidget['patient'],
                )
            );

            $roleID                 = $this->session->userdata('roleID');
            $getpermissionData      = $this->session->userdata('master_permission_set');
            $generatewidgetArray    = [];
            $counter = 0;

            if(isset($widgetArray[$roleID]) && inicompute($widgetArray[$roleID])) {
                foreach($widgetArray[$roleID] as $widgetKey=> $widget) {
                    if(isset($getpermissionData[$widgetKey]) && $getpermissionData[$widgetKey]=='yes') {
                        if($counter == 4) {
                          break;
                        }
                        $generatewidgetArray[$widgetKey] = array(
                            'icon'  => isset($menulogs[$widgetKey]) ? $menulogs[$widgetKey]->icon : '',
                            'color' => $arrayColor[$counter],
                            'link'  => $widgetKey,
                            'count' => $widget,
                            'menu'  => isset($menulogs[$widgetKey]) ? $menulogs[$widgetKey]->name : '',
                        );
                        $counter++;
                    }
                }
            }

            if(inicompute($generatewidgetArray) < 4) {
                if(inicompute($allWidgetArray)) {
                    foreach($allWidgetArray as $widgetKey => $widget) {
                        if(isset($getpermissionData[$widgetKey]) && $getpermissionData[$widgetKey] == 'yes') {
                            if($counter == 4) {
                              break;
                            }
                            if(!isset($generatewidgetArray[$widgetKey])) {
                                $generatewidgetArray[$widgetKey] = array(
                                    'icon'  => isset($menulogs[$widgetKey]) ? $menulogs[$widgetKey]->icon : '',
                                    'color' => $arrayColor[$counter],
                                    'link'  => $widgetKey,
                                    'count' => $widget,
                                    'menu'  => isset($menulogs[$widgetKey]) ? $menulogs[$widgetKey]->name : '',
                                );
                                $counter++;
                            }
                        }
                    }
                }
            }
        ?>
        <?php if(inicompute($generatewidgetArray)) { ?>
            <div class="row sameheight-container">
                <?php foreach($generatewidgetArray as $singlewidget) { ?>
                    <div class="col-md-3">
                        <div class="small-box ">
                            <a class="small-box-footer <?=$singlewidget['color']?>" href="<?=site_url($singlewidget['link'])?>">
                                <div class="icon card-padding <?=$singlewidget['color']?>">
                                    <i class="fa <?=$singlewidget['icon']?>"></i>
                                </div>
                                <div class="inner">
                                    <h3 class="text-white"><?=$singlewidget['count']?></h3>
                                    <p  class="text-white font-size"><?=$this->lang->line('menu_'.$singlewidget['menu'])?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </section>

    <?php if($roleID == 1 || $roleID == 4) { ?>
        <section class="section">
            <div class="row sameheight-container">
                <div class="col-md-12">
                    <div class="card sameheight-item">
                        <div class="card-block">
                            <div id="earningGraph"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <?php if($roleID == 1 || $roleID == 4) { ?>
        <section class="section">
            <div class="row sameheight-container">
                <div class="col-md-4">
                    <div class="card sameheight-item">
                        <?php $this->load->view('dashboard/profileBox'); ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card sameheight-item">
                        <div class="card-block">
                            <div id="attendanceGraph"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="row sameheight-container">
                <div class="col-md-6">
                    <div class="card sameheight-item">
                        <?php $this->load->view('dashboard/noticeBoard'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card sameheight-item">
                        <div class="card-block">
                            <div id="visitor"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } else { ?>
        <section class="section">
            <div class="row sameheight-container">
                <div class="col-md-4">
                    <div class="card sameheight-item">
                        <?php $this->load->view('dashboard/profileBox'); ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card sameheight-item">
                        <?php $this->load->view('dashboard/noticeBoard'); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <section class="section">
        <div class="row sameheight-container">
            <div class="col-md-12">
                <div class="card sameheight-item">
                    <div class="card-block">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

<?php 
    $this->load->view('dashboard/incomeExpenseGraph');
    $this->load->view('dashboard/attendanceGraph');
    $this->load->view('dashboard/visitorGraph');  
    $this->load->view('dashboard/calenderGraph');
?>
