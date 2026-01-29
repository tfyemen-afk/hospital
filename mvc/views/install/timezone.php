<div class="panel panel-default">
    <div class="panel-heading-install">
		<ul class="nav nav-pills">
		  	<li><a href="<?=site_url('install/index')?>"><span class="fa fa-check"></span> Check List</a></li>
            <li><a href="<?=site_url('install/purchasecode')?>"><span class="fa fa-check"></span> Purchase Code</a> </li>
            <li><a href="<?=site_url('install/database')?>"><span class="fa fa-check"></span> Database</a></li>
		  	<li class="active"><a href="<?=site_url('install/timezone')?>">Time Zone</a></li>
		  	<li><a href="#">Site Config</a></li>
            <li><a href="#">System Admin</a></li>
		  	<li><a href="#">Done</a></li>
		</ul>
    </div>
    <div class="panel-body ins-bg-col">
    	<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
            <div class="form-group <?=form_error('timezone') ? 'has-error' : '' ?>">
				<label for="timezone" class="col-sm-2 control-label">
				    <p>Time Zone <span class="text-aqua">*</span></p>
				</label>
				<div class="col-sm-6">
				    <?php
				    	$path = APPPATH."config/timezones_class.php";
				    	if(@include($path)) {
				    		$timezones_cls = new Timezones();
				    		$timezones = $timezones_cls->get_timezones();
				    		echo form_dropdown("timezone", $timezones, set_value("timezone"), "id='timezone' class='form-control'");
				    	}
				    ?>
				</div>
				<span class="col-sm-4 control-label">
				    <?php echo form_error('timezone'); ?>
				</span>
			</div>

			<div class="form-group">
				<div class="col-sm-4">
	                <a href="<?=site_url('install/database')?>" class="btn btn-default pull-right">Previous Step</a>
	            </div>
	            <div class="col-sm-4 col-sm-offset-2">
	                <input type="submit" class="btn btn-success" value="Next Step" >
	            </div>
	        </div>

		</form>
    </div>
</div>