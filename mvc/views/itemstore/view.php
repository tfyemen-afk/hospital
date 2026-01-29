<?php if(inicompute($itemstore)) { ?>
		<div class="profile-view-dis">
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemstore_name')?></span>: <?=$itemstore->name?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemstore_code')?></span>: <?=$itemstore->code?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemstore_in_charge')?></span>: <?=$itemstore->incharge?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemstore_email')?></span>: <?=$itemstore->email?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemstore_phone')?></span>: <?=$itemstore->phone?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemstore_location')?></span>: <?=$itemstore->location?></p>
            </div>
        </div>
	<?php } else { ?>
		<div class="error-card">
            <div class="error-title-block">
                <h1 class="error-title">404</h1>
                <h2 class="error-sub-title"> Sorry, data not found </h2>
            </div>
        </div>
<?php } ?>