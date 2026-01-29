<div class="profile-view-dis">
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('damageandexpire_type')?></span>: <?=($damageandexpire->type==1) ? $this->lang->line('damageandexpire_damage') : $this->lang->line('damageandexpire_expire')?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('damageandexpire_category')?></span>: <?=inicompute($medicinecategory) ? $medicinecategory->name : ''?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('damageandexpire_medicine')?></span>: <?=inicompute($medicine) ? $medicine->name : ''?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('damageandexpire_medicineunit')?></span>: <?=inicompute($medicineunit) ? $medicineunit->medicineunit : ''?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('damageandexpire_batchID')?></span>: <?=$damageandexpire->batchID?></p>
    </div>
    <div class="profile-view-tab">
        <p><span><?=$this->lang->line('damageandexpire_quantity')?></span>: <?=$damageandexpire->quantity?></p>
    </div>
</div>