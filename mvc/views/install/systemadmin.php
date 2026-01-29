<div class="panel panel-default">
    <div class="panel-heading-install">
        <ul class="nav nav-pills">
            <li><a href="<?=site_url('install/index')?>"><span class="fa fa-check"></span> Check List</a></li>
            <li><a href="<?=site_url('install/purchasecode')?>"><span class="fa fa-check"></span> Purchase Code</a> </li>
            <li><a href="<?=site_url('install/database')?>"><span class="fa fa-check"></span> Database</a></li>
            <li><a href="<?=site_url('install/timezone')?>"><span class="fa fa-check"></span> Time Zone</a></li>
            <li><a href="<?=site_url('install/site')?>"><span class="fa fa-check"></span> Site Config</a></li>
            <li class="active"><a href="<?=site_url('install/systemadmin')?>">System Admin</a></li>
            <li><a href="#">Done</a></li>
        </ul>
    </div>
    <div class="panel-body ins-bg-col">
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
            <div class="form-group <?=form_error('name') ? 'has-error' : '' ?>">
                <label for="name" class="col-sm-2 control-label">
                    <p>Name <span class="text-aqua">*</span></p>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>" >
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('name'); ?>
                </span>
            </div>

            <div class="form-group <?=form_error('gender') ? 'has-error' : '' ?>">
                <label for="gender" class="col-sm-2 control-label">
                    <p>Gender <span class="text-aqua">*</span></p>
                </label>
                <div class="col-sm-6">
                    <?php
                        $genderArray['0'] = '— Please Select —';
                        $genderArray['1'] = 'Male';
                        $genderArray['2'] = 'Female';

                        echo form_dropdown('gender', $genderArray,  set_value('gender'), ' class="form-control"')
                    ?>
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('gender'); ?>
                </span>
            </div>

            <div class="form-group <?=form_error('dob') ? 'has-error' : '' ?>">
                <label for="dob" class="col-sm-2 control-label">
                    <p>Date of Birth <span class="text-aqua">*</span></p>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="dob" name="dob" value="<?=set_value('dob')?>" >
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('dob'); ?>
                </span>
            </div>

            <div class="form-group <?=form_error('religion') ? 'has-error' : '' ?>">
                <label for="religion" class="col-sm-2 control-label">
                    <p>Religion</p>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="religion" name="religion" value="<?=set_value('religion')?>" >
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('religion'); ?>
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

            <div class="form-group <?=form_error('jod') ? 'has-error' : '' ?>">
                <label for="jod" class="col-sm-2 control-label">
                    <p>Joining Date <span class="text-aqua">*</span></p>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="jod" name="jod" value="<?=set_value('jod')?>" >
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('jod'); ?>
                </span>
            </div>

            <div class="form-group <?=form_error('username') ? 'has-error' : '' ?>">
                <label for="username" class="col-sm-2 control-label">
                    <p>Username <span class="text-aqua">*</span></p>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="username" name="username" value="<?=set_value('username')?>" >
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('username'); ?>
                </span>
            </div>

            <div class="form-group <?=form_error('password') ? 'has-error' : '' ?>">
                <label for="password" class="col-sm-2 control-label">
                    <p>Password <span class="text-aqua">*</span></p>
                </label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="password" name="password" data-toggle="tooltip" data-placement="right" title="Tooltip on right" value="<?=set_value('password')?>" >
                </div>
                <span class="col-sm-4 control-label">
			        <?php echo form_error('password'); ?>
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
<script type="text/javascript">
  $('#dob').datepicker({ startView: 2 });
  $('#jod').datepicker({ startView: 3 });
</script>
