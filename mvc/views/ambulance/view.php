<?php 
	if(inicompute($ambulance)) { 
		$fueltypeArray      = [];
		$fueltypeArray['1'] = $this->lang->line('ambulance_cng');
	    $fueltypeArray['2'] = $this->lang->line('ambulance_diesel');

	    $typeArray          = [];
	    $typeArray['1']     = $this->lang->line('ambulance_own');
	    $typeArray['2']     = $this->lang->line('ambulance_contractual');

	    $statusArray        = [];
	    $statusArray['1']   = $this->lang->line('ambulance_active');
	    $statusArray['2']   = $this->lang->line('ambulance_inactive'); ?>

		<div class="profile-view-dis">
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_name')?></span>: <?=$ambulance->name?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_number')?></span>: <?=$ambulance->number?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_model')?></span>: <?=$ambulance->model?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_color')?></span>: <?=$ambulance->color?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_cc')?></span>: <?=$ambulance->cc?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_weight')?></span>: <?=$ambulance->weight?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_fuel_type')?></span>: <?=isset($fueltypeArray[$ambulance->fueltype]) ? $fueltypeArray[$ambulance->fueltype] : '' ?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_driver_name')?></span>: <?=$ambulance->drivername?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_driver_licence')?></span>: <?=$ambulance->driverlicence?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_driver_contact')?></span>: <?=$ambulance->drivercontact?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_type')?></span>: <?=isset($typeArray[$ambulance->type]) ? $typeArray[$ambulance->type] : ''?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_note')?></span>: <?=$ambulance->note?></p>
		    </div>
		    <div class="profile-view-tab">
		        <p><span><?=$this->lang->line('ambulance_status')?></span>: <?=isset($statusArray[$ambulance->status]) ? $statusArray[$ambulance->status] : ''?></p>
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