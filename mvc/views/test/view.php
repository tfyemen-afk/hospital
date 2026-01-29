<?php if(inicompute($test)) { ?>
<div class="profile-view-dis">
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('test_test_name')?> </span>: <?=$testlabel->name?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('test_category')?> </span>: <?=$testcategory->name?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('test_date')?> </span>: <?=app_date($test->create_date, false)?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('test_bill_no')?> </span>: <?=$test->billID?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('test_uhid')?> </span>: <?=$test->patientID?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('test_name')?> </span>: <?=$test->name?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('test_note')?> </span>: <?=$test->testnote?></p>
    </div>
</div>
<?php } else { ?>
    <div class="error-card">
        <div class="error-title-block">
            <h1 class="error-title">404</h1>
            <h2 class="error-sub-title"> Sorry, data not found </h2>
        </div>
        <div class="error-container">
            <a class="btn btn-primary" href="<?=site_url('dashboard/index')?>">
            <i class="fa fa-angle-left"></i> Back to Dashboard</a>
        </div>
    </div>
<?php } ?>