<div class="panel panel-default">
    <div class="panel-heading-install">
		<ul class="nav nav-pills">
		  	<li class="active"><a href="<?=site_url('install/index')?>">Check List</a></li>
            <li><a href="#">Purchase Code</a> </li>
		  	<li><a href="#">Database</a></li>
            <li><a href="#">Time Zone</a></li>
		  	<li><a href="#">Site Config</a></li>
		  	<li><a href="#">System Admin</a></li>
		  	<li><a href="#">Done</a></li>
		</ul>
    </div>
    <div class="panel-body ins-bg-col">
    	<?php  
    		foreach ($success as $succ) {
    		 	echo "<div class=\"alert alert-success\"><span class=\"fa fa-check-circle\"></span> ". $succ ."</div>";	
    		}

    		foreach ($errors as $er) {
    		 	echo "<div class=\"alert alert-danger\"><span class=\"fa fa-exclamation-circle\"></span> ". $er ."</div>";
    		}
    	?>
        <?php if(inicompute($errors) > 0) { ?>
    	    <div class="col-sm-12"><a href="<?=site_url('install/index')?>" class="btn btn-success pull-right disabled">Next Step</a></div>
        <?php } else { ?>
            <div class="col-sm-12"><a href="<?=site_url('install/purchasecode')?>" class="btn btn-success pull-right">Next Step</a></div>
        <?php } ?>
    </div>
</div>