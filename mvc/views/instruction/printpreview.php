<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="view-main-area-top-left">
                        <img class="view-main-area-top-img" src="<?=imagelink($patient->photo, 'uploads/user')?>" alt="">
                    </div>
                    <div class="view-main-area-top-right">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('instruction_name')?></div>
                            <div class="single-user-info-value">: <?=$patient->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('instruction_uhid')?></div>
                            <div class="single-user-info-value">: <?=$patient->patientID?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('instruction_designation')?></div>
                            <div class="single-user-info-value">: <?=inicompute($designation) ? $designation->designation : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('instruction_type')?></div>
                            <div class="single-user-info-value">: <?=($patient->patienttypeID == 0) ? $this->lang->line('instruction_opd') : $this->lang->line('instruction_ipd') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('instruction_phone')?></div>
                            <div class="single-user-info-value">: <?=$patient->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                	<?php foreach($instructions as $instruction) { ?>
	                    <div class="media">
	                    	<div class="media-img">
	                        	<img class="media-img-width" src="<?=imagelink($instruction->photo)?>">
	                    	</div>
	                        <div class="media-body">
	                            <div class="media-body-date"><?=app_datetime($instruction->create_date)?></div>
	                            <div class="media-body-name"><?=$instruction->instruction?></div>
	                        </div>
	                    </div>
	                <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>