<div class="panel panel-default">
    <div class="panel-heading-install">
        <ul class="nav nav-pills">
            <li><a href="<?=site_url('install/index')?>"><span class="fa fa-check"></span> Check List</a></li>
            <li class="active"><a href="<?=site_url('install/purchasecode')?>">Purchase Code</a> </li>
            <li><a href="#">Database</a></li>
            <li><a href="#">Time Zone</a></li>
            <li><a href="#">Site Config</a></li>
            <li><a href="#">System Admin</a></li>
            <li><a href="#">Done</a></li>
        </ul>
    </div>
    <div class="panel-body ins-bg-col">

        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
            <div class="form-group <?=form_error('purchase_username') ? 'has-error' : '' ?>">
                <label for="purchase_username" class="col-sm-2 control-label">
                    <p>Username <span class="text-aqua">*</span></p>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="purchase_username" name="purchase_username" value="<?=set_value('purchase_username')?>" >
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('purchase_username'); ?>
                </span>
            </div>

            <div class="form-group <?=form_error('purchase_code') ? 'has-error' : '' ?>">
                <label for="purchase_code" class="col-sm-2 control-label">
                    <p>Purchase Code <span class="text-aqua">*</span></p>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="purchase_code" name="purchase_code" value="<?=set_value('purchase_code')?>" >
                </div>
                <span class="col-sm-4 control-label">
                    <?php echo form_error('purchase_code'); ?>
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