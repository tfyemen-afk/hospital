<?php if(inicompute($itemcheckin)) { ?>
		<div class="profile-view-dis">
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckin_item')?></span>: <?=inicompute($item) ? $item->name : ''?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckin_supplier')?></span>: <?=inicompute($itemsupplier) ? $itemsupplier->companyname : ''?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckin_store')?></span>: <?=inicompute($itemstore) ? $itemstore->name : ''?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckin_quantity')?></span>: <?=$itemcheckin->quantity?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckin_date')?></span>: <?=app_datetime($itemcheckin->date)?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemcheckin_description')?></span>: <?=$itemcheckin->description?></p>
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