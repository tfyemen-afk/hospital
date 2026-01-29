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
            <?php if(inicompute($billitems)) { $i = 0; $totalmainamount = 0; $totalsubtotal = 0;
             foreach($billitems as $billitem) { $randID = random19(); $i++; ?>
                <tr id="tr_<?=$randID?>" billlabelid="<?=$billitem->billlabelID?>">
                    <td><?=$i?></td>
                    <td><?=isset($billlabels[$billitem->billlabelID]) ? $billlabels[$billitem->billlabelID]->name : 'DEL ITEM'?></td>
                    <td>
                        <input type="number" min="0" max="100" class="form-control change-discount" id="discount_<?=$randID?>" value="<?=$billitem->discount?>" data-discountid="<?=$randID?>">
                    </td>
                    <td id="billtotal_<?=$randID?>">
                        <?php
                            $mainamount = $billitem->amount;
                            $amount = explode('.', $mainamount);
                            if($amount[1] === '00') {
                                echo round($mainamount);
                            } else {
                                echo $mainamount;
                            }
                            $totalmainamount +=$mainamount;
                        ?>        
                    </td>
                    <td id="billsubtotal_<?=$randID?>">
                        <?php
                            $subtotal = $billitem->amount;
                            if($billitem->discount > 0) {
                                $subtotal = ($billitem->amount - (($billitem->amount/100) * $billitem->discount));
                            }
                            echo number_format($subtotal, 2);
                            $totalsubtotal += $subtotal;
                        ?>
                    </td>
                    <td>
                        <a href="#" class="btn btn-danger btn-sm margin-delete deleteBtn" id="productaction_<?=$randID?>" data-productaction-id="<?=$randID?>"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>

        <tfoot>
            <tr>
                <td class="font-weight" colspan="3"><?=$this->lang->line('bill_total')?></td>
                <td class="font-weight" id="totalAmount"><?=number_format($totalmainamount, 2)?></td>
                <td class="font-weight" id="totalSubtotal"><?=number_format($totalsubtotal, 2)?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
