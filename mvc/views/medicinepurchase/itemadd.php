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
        </tbody>

        <tfoot>
            <tr>
                <td class="font-weight" colspan="5"><?=$this->lang->line('medicinepurchase_total')?></td>
                <td class="font-weight" id="totalQuantity">0.00</td>
                <td class="font-weight" id="totalSubtotal">0.00</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
