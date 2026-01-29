<form role="form" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group <?=form_error('billcategoryID') ? 'has-error' : '' ?>" >
                <label for="billcategoryID" class="control-label">
                    <?=$this->lang->line("bill_bill_category")?> <span class="text-danger"> *</span>
                </label>
                <?php
                    $billcategoryArray['0'] =  '— '.$this->lang->line('bill_please_select').' —';
                    if(inicompute($billcategorys)) {
                        foreach ($billcategorys as $billcategory) {
                            $billcategoryArray[$billcategory->billcategoryID] = $billcategory->name;
                        }
                    }
                    echo form_dropdown("billcategoryID", $billcategoryArray, set_value("billcategoryID"), "id='billcategoryID' class='form-control select2'");
                ?>
                <span class="control-label">
                    <?php echo form_error('billcategoryID'); ?>
                </span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?=form_error('billlabelID') ? 'has-error' : '' ?>" >
                <label for="billlabelID" class="control-label">
                    <?=$this->lang->line("bill_bill_label")?> <span class="text-danger"> *</span>
                </label>
                <?php
                    $productArray = array(0 => $this->lang->line("bill_please_select"));
                    echo form_dropdown("billlabelID", $productArray, set_value("billlabelID"), "id='billlabelID' class='form-control select2'");
                ?>
                <span class="control-label">
                    <?php echo form_error('billlabelID'); ?>
                </span>
            </div>  
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover simple-table">
        <thead>
            <tr>
                <th class="small-size"><?=$this->lang->line('bill_slno')?></th>
                <th><?=$this->lang->line('bill_name')?></th>
                <th><?=$this->lang->line('bill_discount')?>(%)</th>
                <th><?=$this->lang->line('bill_amount')?></th>
                <th><?=$this->lang->line('bill_subtotal')?></th>
                <th class="small-size"><?=$this->lang->line('bill_action')?></th>
            </tr>
        </thead>

        <tbody id="billList">
        </tbody>

        <tfoot>
            <tr>
                <td class="font-weight" colspan="3"><?=$this->lang->line('bill_total')?></td>
                <td class="font-weight" id="totalAmount">0.00</td>
                <td class="font-weight" id="totalSubtotal">0.00</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>