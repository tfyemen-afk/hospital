<?php if(inicompute($itemsupplier)) { ?>
		<div class="profile-view-dis">
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemsupplier_company_name')?></span>: <?=$itemsupplier->companyname?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemsupplier_supplier_name')?></span>: <?=$itemsupplier->suppliername?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemsupplier_email')?></span>: <?=$itemsupplier->email?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemsupplier_phone')?></span>: <?=$itemsupplier->phone?></p>
            </div>
            <div class="profile-view-tab">
                <p><span><?=$this->lang->line('itemsupplier_address')?></span>: <?=$itemsupplier->address?></p>
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