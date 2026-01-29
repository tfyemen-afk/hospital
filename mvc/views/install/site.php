<div class="panel panel-default">
    <div class="panel-heading-install">
		<ul class="nav nav-pills">
		  	<li><a href="<?=site_url('install/index')?>"><span class="fa fa-check"></span> Check List</a></li>
            <li><a href="<?=site_url('install/purchasecode')?>"><span class="fa fa-check"></span> Purchase Code</a> </li>
            <li><a href="<?=site_url('install/database')?>"><span class="fa fa-check"></span> Database</a></li>
            <li><a href="<?=site_url('install/timezone')?>"><span class="fa fa-check"></span> Time Zone</a></li>
		  	<li class="active"><a href="<?=site_url('install/site')?>">Site Config</a></li>
            <li><a href="#">System Admin</a></li>
		  	<li><a href="#">Done</a></li>
		</ul>
    </div>
    <div class="panel-body ins-bg-col">
    	<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
            <div class="form-group <?=form_error('system_name') ? 'has-error' : '' ?>">
				<label for="system_name" class="col-sm-2 control-label">
				    <p>Site Title <span class="text-aqua">*</span></p>
				</label>
				<div class="col-sm-6">
				    <input type="text" class="form-control" id="system_name" name="system_name" value="<?=set_value('system_name')?>" >
				</div>
				<span class="col-sm-4 control-label">
				    <?php echo form_error('system_name'); ?>
				</span>
			</div>

            <div class="form-group <?=form_error('phone') ? 'has-error' : '' ?>">
			    <label for="phone" class="col-sm-2 control-label">
			        <p>Phone <span class="text-aqua">*</span></p>
			    </label>
			    <div class="col-sm-6">
			        <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone')?>" >
			    </div>
			    <span class="col-sm-4 control-label">
			        <?php echo form_error('phone'); ?>
			    </span>
			</div>

            <div class="form-group <?=form_error('email') ? 'has-error' : '' ?>">
			    <label for="email" class="col-sm-2 control-label">
			        <p>Email <span class="text-aqua">*</span></p>
			    </label>
			    <div class="col-sm-6">
			        <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email')?>" >
			    </div>
			    <span class="col-sm-4 control-label">
			        <?php echo form_error('email'); ?>
			    </span>
			</div>

            <div class="form-group <?=form_error('currency_code') ? 'has-error' : '' ?>">
			    <label for="currency_code" class="col-sm-2 control-label">
			        <p>Currency Code</p>
			    </label>
			    <div class="col-sm-6">
			        <input type="text" class="form-control" id="currency_code" name="currency_code" value="<?=set_value('currency_code')?>" >
			    </div>
			     <span class="col-sm-4 control-label">
			        <?php echo form_error('currency_code'); ?>
			    </span>
			</div>

            <div class="form-group <?=form_error('currency_symbol') ? 'has-error' : '' ?>">
			    <label for="currency_symbol" class="col-sm-2 control-label">
			        <p>Currency Symbol</p>
			    </label>
			    <div class="col-sm-6">
			        <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" value="<?=set_value('currency_symbol')?>" >
			    </div>
			     <span class="col-sm-4 control-label">
			        <?php echo form_error('currency_symbol'); ?>
			    </span>
			</div>

            <div class="form-group <?=form_error('address') ? 'has-error' : '' ?>">
			    <label for="address" class="col-sm-2 control-label">
			        <p>Address</p>
			    </label>
			    <div class="col-sm-6">
			        <textarea name="address" class="form-control" id="address"><?=set_value('address')?></textarea>
			    </div>
			     <span class="col-sm-4 control-label">
			        <?php echo form_error('address'); ?>
			    </span>
			</div>

			<div class="form-group">
				<div class="col-sm-4">
	                <a href="<?=site_url('install/timezone')?>" class="btn btn-default pull-right">Previous Step</a>
	            </div>
	            <div class="col-sm-4 col-sm-offset-2">
	                <input type="submit" class="btn btn-success" value="Next Step" >
	            </div>
	        </div>

		</form>
    </div>
</div>