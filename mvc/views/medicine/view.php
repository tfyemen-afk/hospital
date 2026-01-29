<div class="profile-view-dis">
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('medicine_name')?></span>: <?=$medicine->name?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('medicine_category')?></span>: <?=inicompute($medicinecategory) ? $medicinecategory->name : ""?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('medicine_manufacturer')?></span>: <?=inicompute($medicinemanufacturer) ? $medicinemanufacturer->name : ""?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('medicine_medicineunit')?></span>: <?=inicompute($medicineunit) ? $medicineunit->medicineunit : ""?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('medicine_buying_price')?></span>: <?=$medicine->buying_price?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('medicine_selling_price')?></span>: <?=$medicine->selling_price?></p>
    </div>
</div>