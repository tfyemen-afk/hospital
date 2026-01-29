
<?php if(inicompute($pageArrays)) { ?> 
	<?php foreach ($pageArrays as $pageArray) { $rand = rand('999', '999999999999'); $rand2 = rand('999', '999999999999'); ?>
		<li class="mjs-nestedSortable-leaf" id="<?=md5($rand.'-'.$rand2)?>">
			<div class="panel panel-default ui-sortable-handle <?=$pageArray->pageID.'-'.$rand?>" data-id="<?=$pageArray->pageID?>" data-type-id="1" data-rand="<?=md5($rand.'-'.$rand2)?>">
				<div class="panel-heading ui-sortable-handle" role="tab" id="menu-item-heading-<?=$pageArray->pageID.'-'.$rand?>">
				  	<h4 class="panel-title">
					    <a class="menu-click-button-menu collapsed menu-title-<?=$pageArray->pageID.'-'.$rand?>" role="button" data-toggle="collapse" data-parent="#menu-item" href="#menu-item-collapse-<?=$pageArray->pageID.'-'.$rand?>" aria-expanded="false" aria-controls="menu-item-collapse-<?=$pageArray->pageID.'-'.$rand?>">
					    	<span class="menu-manage-title-text"><?=$pageArray->title?></span>
					    	<span class="pull-right"><?=$this->lang->line('frontendmenu_page')?></span>
					      	<i class="fa fa-angle-right"></i>
					    </a>
					 </h4>
				</div>
				<div id="menu-item-collapse-<?=$pageArray->pageID.'-'.$rand?>" class="panel-collapse collapse ui-sortable-handle" role="tabpanel" aria-labelledby="menu-item-heading-<?=$pageArray->pageID.'-'.$rand?>">
					<div class="panel-body ui-sortable-handle">
						<form class="menu-page">
				    		<div class="form-group">
				    			<label for="label-<?=$pageArray->pageID.'-'.$rand?>"><?=$this->lang->line('frontendmenu_navigation_label')?></label>
				    			<input type="text" name="" class="form-control lable-text" id="label-<?=$pageArray->pageID.'-'.$rand?>" value="<?=$pageArray->title?>" data-title="menu-title-<?=$pageArray->pageID.'-'.$rand?>">
				    		</div>
				    		<div class="form-group ui-sortable-handle">
				    			<div class="move ui-sortable-handle">
				    				<span><?=$this->lang->line('frontendmenu_move')?></span>
				    				<a href="#" rand-info="<?=$pageArray->pageID.'-'.$rand?>" class="data-up-one"><?=$this->lang->line('frontendmenu_up_one')?></a>
				    				<a href="#" rand-info="<?=$pageArray->pageID.'-'.$rand?>" class="data-down-one"><?=$this->lang->line('frontendmenu_down_one')?></a>
				    			</div>
				    		</div>
				    		<div class="form-group ui-sortable-handle">
				    			<div class="link-to-original ui-sortable-handle">
				    				<span><?=$this->lang->line('frontendmenu_orginal')?>:</span>
				    				<a target="_blank" href="<?=site_url('frontend/page/'.$pageArray->url)?>"><?=$pageArray->title?></a>
				    			</div>
				    		</div>
				    		<div class="form-group ui-sortable-handle">
				    			<a class="Data-remove" href="#" data-title="<?=$pageArray->pageID.'-'.$rand?>"><?=$this->lang->line('frontendmenu_remove')?></a>
				    			<span>|</span>
				    			<a old-title="<?=$pageArray->title?>" class="Data-cancel" rand-info="<?=$pageArray->pageID.'-'.$rand?>" href="#"><?=$this->lang->line('frontendmenu_cancel')?></a>
				    		</div>
				    	</form>
					</div>
				</div>
			</div>
		</li>		
	<?php } ?>
<?php } ?>