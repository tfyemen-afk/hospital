<?php if(inicompute($ward)) { ?>
    <div class="profile-view-dis">
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('ward_name')?> </span>: <?=$ward->name?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('ward_floor')?> </span>: <?=(inicompute($floor)) ? $floor->name : ''?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('ward_room')?> </span>: <?=(inicompute($room)) ? $room->name : ''?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('ward_description')?> </span>: <?=$ward->description?></p>
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