<?php if(inicompute($itemcheckout)) { ?>
		<div class="profile-view-dis">
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckout_user')?></span>: <?=inicompute($user) ? $user->name : ''?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckout_item')?></span>: <?=inicompute($item) ? $item->name : ''?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckout_issue_date')?></span>: <?=app_datetime($itemcheckout->issuedate)?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckout_return_date')?></span>: <?=app_datetime($itemcheckout->returndate)?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckout_quantity')?></span>: <?=$itemcheckout->quantity?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckout_note')?></span>: <?=$itemcheckout->note?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckout_return_date')?></span>: <?=app_datetime($itemcheckout->currentreturndate)?></p>
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