<div class="row">
    <div class="col-sm-6">
        <div class="form-group <?=form_error('medicinecategoryID') ? 'has-error' : '' ?>" >
            <label for="medicinecategoryID" class="control-label">
                <?=$this->lang->line("medicinepurchase_category")?> <span class="text-danger">*</span>
            </label>
            <?php
                $medicinecategoryArray['0'] = '— '.$this->lang->line("medicinepurchase_please_select").' —';
                if(inicompute($medicinecategorys)) {
                    foreach ($medicinecategorys as $medicinecategory) {
                        $medicinecategoryArray[$medicinecategory->medicinecategoryID] = $medicinecategory->name;
                    }
                }
                echo form_dropdown("medicinecategoryID", $medicinecategoryArray, set_value("medicinecategoryID"), " id='medicinecategoryID' class='form-control select2'");
            ?>
            <span class="control-label">
                <?php echo form_error('medicinecategoryID'); ?>
            </span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group <?=form_error('medicineID') ? 'has-error' : '' ?>" >
            <label for="medicineID" class="control-label">
                <?=$this->lang->line("medicinepurchase_medicine")?> <span class="text-danger">*</span>
            </label>
            <?php
                $medicineArray['0'] = '— '.$this->lang->line("medicinepurchase_please_select").' —';
                echo form_dropdown("medicineID", $medicineArray, set_value("medicineID"), "id='medicineID' class='form-control select2'");
            ?>
            <span class="control-label">
                <?php echo form_error('medicineID'); ?>
            </span>
        </div>  
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover simple-table">
        <thead>
            <tr>
                <th class="small-size"><?=$this->lang->line('medicinepurchase_slno')?></th>
                <th><?=$this->lang->line('medicinepurchase_name')?></th>
                <th><?=$this->lang->line('medicinepurchase_batchID')?></th>
                <th><?=$this->lang->line('medicinepurchase_expire_date')?></th>
                <th><?=$this->lang->line('medicinepurchase_unit_price')?></th>
                <th><?=$this->lang->line('medicinepurchase_quantity')?></th>
                <th><?=$this->lang->line('medicinepurchase_subtotal')?></th>
                <th class="small-size"><?=$this->lang->line('medicinepurchase_action')?></th>
            </tr>
        </thead>
        <tbody id="medicineList">
            <?php $totalQuantity= 0; $totalAmount = 0; 
                if(inicompute($medicinepurchaseitems)) { $i=1; foreach($medicinepurchaseitems as $medicinepurchaseitem) { $randID = random19(); ?>
                <tr id="tr_<?=$randID?>" medicineid="<?=$medicinepurchaseitem->medicineID?>" medicineitemid="<?=$medicinepurchaseitem->medicinepurchaseitemID?>" randid="<?=$randID?>">
                    <td><?=$i++?></td>
                    <td><?=isset($medicines[$medicinepurchaseitem->medicineID]) ? $medicines[$medicinepurchaseitem->medicineID]->name : ''?></td>
                    <td>
                        <input type="text" <?=($medicinepurchaseitem->salequantity > 0) ? 'disabled' : ''?> class="form-control global-white-background change-medicinebatchID" id="medicinebatchID_<?=$randID?>" value="<?=$medicinepurchaseitem->batchID?>"></td>
                    <td>
                        <input type="text" class="form-control change-medicineexpiredate datepicker" id="medicineexpiredate_<?=$randID?>" value="<?=date('d-m-Y', strtotime($medicinepurchaseitem->expire_date))?>">
                    </td>
                    <td>
                        <input type="text" class="form-control change-medicineprice" id="medicineprice_<?=$randID?>" value="<?=$medicinepurchaseitem->unit_price?>" data-medicineprice-id="<?=$randID?>">
                    </td>
                    <td>
                        <input type="text" class="form-control change-medicinequantity" id="medicinequantity_<?=$randID?>" value="<?=$medicinepurchaseitem->quantity?>" data-medicinequantity-id="<?=$randID?>" data-salequantity="<?=$medicinepurchaseitem->salequantity?>">
                    </td>
                    <td id="medicinetotal_<?=$randID?>">
                        <?=number_format($medicinepurchaseitem->subtotal, 2)?>
                    </td>
                    <td>
                        <a href="#" class="btn btn-danger btn-sm deleteBtn" <?=($medicinepurchaseitem->salequantity > 0) ? 'disabled' : ''?> id="medicineaction_<?=$randID?>" data-medicineaction-id="<?=$randID?>"><i class="fa fa-trash-o"></i></a>
                    </td>

                    <?php 
                        $totalQuantity += $medicinepurchaseitem->quantity;
                        $totalAmount   += $medicinepurchaseitem->subtotal;
                    ?>
                </tr>
            <?php } } ?>
        </tbody>

        <tfoot>
            <tr>
                <td class="font-weight" colspan="5"><?=$this->lang->line('medicinepurchase_total')?></td>
                <td class="font-weight" id="totalQuantity"><?=number_format($totalQuantity, 2)?></td>
                <td class="font-weight" id="totalSubtotal"><?=number_format($totalAmount, 2)?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>