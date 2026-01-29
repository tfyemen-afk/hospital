<?php $rand = rand('999', '999999999999'); ?>
<li class="<?=isset($mergeelement['child']) ? 'mjs-nestedSortable-branch mjs-nestedSortable-expanded' : 'mjs-nestedSortable-leaf'?>" id="<?=$mergeelement['menu_rand']?>">
	<div class="panel panel-default ui-sortable-handle <?=$mergeelement['menu_pageID'].'-'.$rand?>" data-id="<?=$mergeelement['menu_pageID']?>" data-type-id="1" data-rand="<?=$mergeelement['menu_rand']?>">
		<div class="panel-heading ui-sortable-handle" role="tab" id="menu-item-heading-<?=$mergeelement['menu_pageID'].'-'.$rand?>">
		  	<h4 class="panel-title">
			    <a class="menu-click-button-menu collapsed menu-title-<?=$mergeelement['menu_pageID'].'-'.$rand?>" role="button" data-toggle="collapse" data-parent="#menu-item" href="#menu-item-collapse-<?=$mergeelement['menu_pageID'].'-'.$rand?>" aria-expanded="false" aria-controls="menu-item-collapse-<?=$mergeelement['menu_pageID'].'-'.$rand?>">
			    	<span class="menu-manage-title-text"><?=$mergeelement['menu_label']?></span>
			    	<span class="pull-right"><?=$this->lang->line('frontendmenu_page')?></span>
			      	<i class="fa fa-angle-right"></i>
			    </a>
			 </h4>
		</div>
		<div id="menu-item-collapse-<?=$mergeelement['menu_pageID'].'-'.$rand?>" class="panel-collapse collapse ui-sortable-handle" role="tabpanel" aria-labelledby="menu-item-heading-<?=$mergeelement['menu_pageID'].'-'.$rand?>">
			<div class="panel-body ui-sortable-handle">
				<form class="menu-page">
		    		<div class="form-group">
		    			<label for="label-<?=$mergeelement['menu_pageID'].'-'.$rand?>"><?=$this->lang->line('frontendmenu_navigation_label')?></label>
		    			<input type="text" name="" class="form-control lable-text" id="label-<?=$mergeelement['menu_pageID'].'-'.$rand?>" value="<?=$mergeelement['menu_label']?>" data-title="menu-title-<?=$mergeelement['menu_pageID'].'-'.$rand?>">
		    		</div>
		    		<div class="form-group ui-sortable-handle">
		    			<div class="move ui-sortable-handle">
		    				<span><?=$this->lang->line('frontendmenu_move')?></span>
		    				<a href="#" rand-info="<?=$mergeelement['menu_pageID'].'-'.$rand?>" class="data-up-one"><?=$this->lang->line('frontendmenu_up_one')?></a>
		    				<a href="#" rand-info="<?=$mergeelement['menu_pageID'].'-'.$rand?>" class="data-down-one"><?=$this->lang->line('frontendmenu_down_one')?></a>
		    			</div>
		    		</div>
		    		<div class="form-group ui-sortable-handle">
		    			<div class="link-to-original ui-sortable-handle">
		    				<span><?=$this->lang->line('frontendmenu_orginal')?>:</span>
		    				<a target="_blank" href="<?=isset($pluckpages[$mergeelement['menu_pageID']]) ? site_url('frontend/page/'.$pluckpages[$mergeelement['menu_pageID']]->url) : site_url('frontend/page/#')?>"><?=isset($pluckpages[$mergeelement['menu_pageID']]) ? $pluckpages[$mergeelement['menu_pageID']]->title : $mergeelement['menu_label']?></a>
		    			</div>
		    		</div>
		    		<div class="form-group ui-sortable-handle">
		    			<a class="Data-remove" href="#" data-title="<?=$mergeelement['menu_pageID'].'-'.$rand?>"><?=$this->lang->line('frontendmenu_remove')?></a>
		    			<span>|</span>
		    			<a old-title="<?=$mergeelement['menu_label']?>" class="Data-cancel" rand-info="<?=$mergeelement['menu_pageID'].'-'.$rand?>" href="#"><?=$this->lang->line('frontendmenu_cancel')?></a>
		    		</div>
		    	</form>
			</div>
		</div>
	</div>
