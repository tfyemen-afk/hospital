<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-medicinestock"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('medicinestock/index')?>"><?=$this->lang->line('menu_medicinestock')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('medicinestock_view')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-sliders"></i>&nbsp;<?=$this->lang->line('medicinestock_filter')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item list-group-item-background">
                                <b><?=$this->lang->line('medicinestock_name')?></b>
                                <a class="pull-right"><?=$medicine->name?></a>
                            </li>
                            <li class="list-group-item list-group-item-background">
                                <b><?=$this->lang->line('medicinestock_category')?></b>
                                <a class="pull-right"><?=inicompute($medicinecategory) ? $medicinecategory->name : ''?></a>
                            </li>
                            <li class="list-group-item list-group-item-background">
                                <b><?=$this->lang->line('medicinestock_manufacturer')?></b>
                                <a class="pull-right"><?=inicompute($medicinemanufacturer) ? $medicinemanufacturer->name : ''?></a>
                            </li>
                            <li class="list-group-item list-group-item-background">
                                <b><?=$this->lang->line('medicinestock_unit')?></b>
                                <a class="pull-right"><?=inicompute($medicineunit) ? $medicineunit->medicineunit : ''?></a>
                            </li>
                            <li class="list-group-item list-group-item-background">
                                <b><?=$this->lang->line('medicinestock_total_available_quantity')?></b>
                                <a class="pull-right"><?=$total_available_quantity?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-braille"></i>&nbsp;<?=$this->lang->line('medicinestock_filter_data')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <div id="hide-table">
                            <table class="table table-striped table-bordered example text-white">
                                <thead>
                                    <tr class="medicine-table-header">
                                        <th><?=$this->lang->line('medicinestock_slno')?></th>
                                        <th><?=$this->lang->line('medicinestock_batch')?></th>
                                        <th><?=$this->lang->line('medicinestock_total_quantity')?></th>
                                        <th><?=$this->lang->line('medicinestock_sale_quantity')?></th>
                                        <th><?=$this->lang->line('medicinestock_damage')?></th>
                                        <th><?=$this->lang->line('medicinestock_expire')?></th>
                                        <th><?=$this->lang->line('medicinestock_available_quantity')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; if(inicompute($medicinepurchaseitems)) {foreach($medicinepurchaseitems as $medicinepurchaseitem) { $i++;
                                        if(isset($medicinepurchase[$medicinepurchaseitem->medicinepurchaseID])) { continue; } ?>
                                        <tr class="<?=($medicinepurchaseitem->status == 1) ? 'bg-danger' : table_row_bgcolor($medicinepurchaseitem->expire_date, $setting_expire_date);?>">
                                            <td data-title="<?=$this->lang->line('medicinestock_slno')?>">
                                                <?=$i;?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('medicinestock_batch')?>">
                                                <?=$medicinepurchaseitem->batchID?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('medicinestock_total_quantity')?>">
                                                <?=$medicinepurchaseitem->quantity?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('medicinestock_sale_quantity')?>">
                                                <?php 
                                                    $totaldamgeandexpire = isset($damageandexpire[$medicinepurchaseitem->medicineID][$medicinepurchaseitem->batchID]['totaldamgeandexpire']) ? $damageandexpire[$medicinepurchaseitem->medicineID][$medicinepurchaseitem->batchID]['totaldamgeandexpire'] : 0;
                                                    echo ($medicinepurchaseitem->salequantity - $totaldamgeandexpire);
                                                ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('medicinestock_damage')?>">
                                                <?=isset($damageandexpire[$medicinepurchaseitem->medicineID][$medicinepurchaseitem->batchID][1]) ? $damageandexpire[$medicinepurchaseitem->medicineID][$medicinepurchaseitem->batchID][1] : 0?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('medicinestock_expire')?>">
                                                <?=isset($damageandexpire[$medicinepurchaseitem->medicineID][$medicinepurchaseitem->batchID][2]) ? $damageandexpire[$medicinepurchaseitem->medicineID][$medicinepurchaseitem->batchID][2] : 0?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('medicinestock_available_quantity')?>">
                                                <?=$medicinepurchaseitem->quantity - $medicinepurchaseitem->salequantity?>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>