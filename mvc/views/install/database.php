<div class="panel panel-default">
    <div class="panel-heading-install">
		<ul class="nav nav-pills">
		  	<li><a href="<?=site_url('install/index')?>"><span class="fa fa-check"></span> Check List</a></li>
            <li><a href="<?=site_url('install/purchasecode')?>"><span class="fa fa-check"></span> Purchase Code</a> </li>
            <li class="active"><a href="<?=site_url('install/database')?>">Database</a></li>
		  	<li><a href="#">Time Zone</a></li>
		  	<li><a href="#">Site Config</a></li>
            <li><a href="#">System Admin</a></li>
		  	<li><a href="#">Done</a></li>
		</ul>
    </div>
    <div class="panel-body ins-bg-col">
    	<form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
            <div class="form-group <?=form_error('host') ? 'has-error' : '' ?>">
				<label for="host" class="col-sm-2 control-label">
				    <p>Hostname <span class="text-aqua">*</span></p>
				</label>
				<div class="col-sm-6">
				    <input type="text" class="form-control" id="host" name="host" value="<?=set_value('host')?>" >
				</div>
				<span class="col-sm-4 control-label">
				    <?php echo form_error('host'); ?>
				</span>
			</div>

            <div class="form-group <?=form_error('database') ? 'has-error' : '' ?>">
				<label for="database" class="col-sm-2 control-label">
				    <p>Database <span class="text-aqua">*</span></p>
				</label>
				<div class="col-sm-6">
				    <input type="text" class="form-control" id="database" name="database" value="<?=set_value('database')?>" >
				</div>
				<span class="col-sm-4 control-label">
				    <?php echo form_error('database'); ?>
				</span>
			</div>

            <div class="form-group <?=form_error('user') ? 'has-error' : '' ?>">
				<label for="user" class="col-sm-2 control-label">
				    <p>Username <span class="text-aqua">*</span></p>
				</label>
				<div class="col-sm-6">
				    <input type="text" class="form-control" id="user" name="user" value="<?=set_value('user')?>" >
				</div>
				<span class="col-sm-4 control-label">
				    <?php echo form_error('user'); ?>
				</span>
			</div>

            <div class="form-group <?=form_error('password') ? 'has-error' : '' ?>">
				<label for="password" class="col-sm-2 control-label">
				    <p>Password <span class="text-aqua">*</span></p>
				</label>
				<div class="col-sm-6">
				    <input type="password" class="form-control" id="password" name="password" value="<?=set_value('password')?>" >
				</div>
				<span class="col-sm-4 control-label">
				    <?php echo form_error('password'); ?>
				</span>
			</div>
	        <div class="form-group">
				<div class="col-sm-4">
	                <a href="<?=site_url('install/index')?>" class="btn btn-default pull-right">Previous Step</a>
	            </div>
	            <div class="col-sm-4 col-sm-offset-2">
	                <input type="submit" class="btn btn-success" value="Next Step" >
	            </div>
			
	        </div>
		</form>
    </div>
</div>