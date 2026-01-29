<div class="row">
    <div class="col-sm-6">
        <div class="form-group <?=form_error('medicinecategoryID') ? 'has-error' : '' ?>" >
            <label for="medicinecategoryID" class="control-label">
                <?=$this->lang->line("medicinesale_category")?> <span class="text-danger">*</span>
            </label>
            <?php
                $medicinecategoryArray['0'] = '— '.$this->lang->line("medicinesale_please_select").' —';
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
                <?=$this->lang->line("medicinesale_medicine")?> <span class="text-danger">*</span>
            </label>
            <?php
                $medicineArray['0'] = '— '.$this->lang->line("medicinesale_please_select").' —';
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
                <th class="small-size"><?=$this->lang->line('medicinesale_slno')?></th>
                <th><?=$this->lang->line('medicinesale_name')?></th>
                <th><?=$this->lang->line('medicinesale_batchID')?></th>
                <th><?=$this->lang->line('medicinesale_expire_date')?></th>
                <th><?=$this->lang->line('medicinesale_unit_price')?></th>
                <th><?=$this->lang->line('medicinesale_quantity')?></th>
                <th><?=$this->lang->line('medicinesale_subtotal')?></th>
                <th class="small-size"><?=$this->lang->line('medicinesale_action')?></th>
            </tr>
        </thead>
        <tbody id="medicineList">
            <?php $totalQuantity= 0; $totalAmount = 0; 
                if(inicompute($medicinesaleitems)) { $i=1; foreach($medicinesaleitems as $medicinesaleitem) { $randID = random19(); ?>

                <tr id="tr_<?=$randID?>" medicineid="<?=$medicinesaleitem->medicineID?>" medicineitemid="<?=$medicinesaleitem->medicinepurchaseitemID?>" randid="<?=$randID?>">
                    <td><?=$i++?></td>
                    <td><?=isset($medicines[$medicinesaleitem->medicineID]) ? $medicines[$medicinesaleitem->medicineID]->name : ''?></td>
                    <td>
                        <select class="form-control small-select2 change-medicinebatchID" id="medicinebatchID_<?=$randID?>">
                        <option value="0"><?=$this->lang->line('medicinesale_please_select')?></option>
                            <?php if(isset($batchs[$medicinesaleitem->medicineID]) && inicompute($batchs[$medicinesaleitem->medicineID])) { 
                                foreach ($batchs[$medicinesaleitem->medicineID] as $batch) { ?>
                                <option data-medicineitemid="<?=$batch['medicineitemid']?>" data-expiredate="<?=$batch['expiredate']?>" data-quantity="<?=$batch['quantity']?>" value="<?=$batch['batchID']?>" <?=($batch['batchID'] == $medicinesaleitem->medicinebatchID) ? 'selected' : ''?>><?=$batch['batchID']?></option>
                            <?php } } ?>
                        </select>
                    </td>

                    <td>
                        <input style="background-color: #fff;" type="text" class="form-control change-medicineexpiredate" value="<?=date('d-m-Y', strtotime($medicinesaleitem->medicineexpiredate))?>" readonly id="medicineexpiredate_<?=$randID?>">
                    </td>

                    <td>
                        <input type="text" class="form-control change-medicineprice" id="medicineprice_<?=$randID?>" value="<?=$medicinesaleitem->medicinesaleunitprice?>" data-medicineprice-id="<?=$randID?>">
                    </td>

                    <td>
                        <?php $max = isset($maxQuantity[$medicinesaleitem->medicineID][$medicinesaleitem->medicinebatchID]) ? $maxQuantity[$medicinesaleitem->medicineID][$medicinesaleitem->medicinebatchID] : 0?>
                        <input type="text" class="form-control change-medicinequantity" value="<?=$medicinesaleitem->medicinesalequantity?>" id="medicinequantity_<?=$randID?>" max="<?=$max?>" data-medicinequantity-id="<?=$randID?>">
                    </td>

                    <td id="medicinetotal_<?=$randID?>">
                        <?=$medicinesaleitem->medicinesalesubtotal?>
                    </td>

                    <td>
                        <a href="#" class="btn btn-danger btn-sm deleteBtn" id="medicineaction_<?=$randID?>" data-medicineaction-id="<?=$randID?>"><i class="fa fa-trash-o"></i></a>
                    </td>
                    <?php 
                        $totalQuantity += $medicinesaleitem->medicinesalequantity;
                        $totalAmount   += $medicinesaleitem->medicinesalesubtotal;
                    ?>
                </tr>
            <?php } } ?>
        </tbody>

        <tfoot id="productListFooter">
            <tr>
                <td colspan="5" style="font-weight: bold"><?=$this->lang->line('medicinesale_total')?></td>
                <td id="totalQuantity" style="font-weight: bold"><?=number_format($totalQuantity, 2)?></td>
                <td id="totalSubtotal" style="font-weight: bold"><?=number_format($totalAmount, 2)?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>