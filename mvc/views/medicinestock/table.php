<div id="hide-table">
    <table class="table table-striped table-bordered example2">
        <thead>
            <tr>
                <th><?=$this->lang->line('medicinestock_slno')?></th>
                <th><?=$this->lang->line('medicinestock_medicine')?></th>
                <th><?=$this->lang->line('medicinestock_category')?></th>
                <th><?=$this->lang->line('medicinestock_manufacturer')?></th>
                <th><?=$this->lang->line('medicinestock_unit')?></th>
                <th><?=$this->lang->line('medicinestock_status')?></th>
                <th><?=$this->lang->line('medicinestock_action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($medicines)) {foreach($medicines as $medicine) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('medicinestock_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinestock_medicine')?>">
                        <?=$medicine->name?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinestock_category')?>">
                        <?=isset($medicinecategorys[$medicine->medicinecategoryID]) ? $medicinecategorys[$medicine->medicinecategoryID] : ''?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinestock_manufacturer')?>">
                        <?=isset($medicinemanufacturers[$medicine->medicinemanufacturerID]) ? $medicinemanufacturers[$medicine->medicinemanufacturerID] : ''?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinestock_unit')?>">
                        <?=isset($medicineunits[$medicine->medicineunitID]) ? $medicineunits[$medicine->medicineunitID] : ''?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinestock_status')?>">
                        <?php 
                            if(isset($medicinestatus[$medicine->medicineID])) {
                                if($medicinestatus[$medicine->medicineID] ==1 ) {
                                    echo '<span class="text-success">'.$this->lang->line('medicinestock_available').'</span>';
                                } else if($medicinestatus[$medicine->medicineID] == 2) {
                                    echo '<span class="text-danger">'.$this->lang->line('medicinestock_expire').'</span>';
                                } else {
                                    echo '<span class="text-danger">'.$this->lang->line('medicinestock_stock_out').'</span>';
                                }
                            }
                        ?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinestock_action')?>">
                        <a href="<?=site_url('medicinestock/view/'.$medicine->medicineID)?>" class="btn btn-success btn-custom mrg" data-placement="top" data-toggle="tooltip" title="<?=$this->lang->line('medicinestock_view')?>"><i class="fa fa-check-square-o"></i></a>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>