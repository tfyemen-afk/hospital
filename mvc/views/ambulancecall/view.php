<?php 
	if(inicompute($ambulancecall)) { ?>
		<div class="profile-view-dis">
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulancecall_ambulance')?></span>: <?=inicompute($ambulance) ? $ambulance->name : '' ?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulancecall_driver_name')?></span>: <?=$ambulancecall->drivername?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulancecall_date')?></span>: <?=app_datetime($ambulancecall->date)?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulancecall_amount')?></span>: <?=$ambulancecall->amount?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulancecall_patient_name')?></span>: <?=$ambulancecall->patientname?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulancecall_patient_contact')?></span>: <?=$ambulancecall->patientcontact?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulancecall_pickup_point')?></span>: <?=$ambulancecall->pickup_point?></p>
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