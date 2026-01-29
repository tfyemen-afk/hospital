<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-emailsetting"></i> <?=$this->lang->line('panel_title')?></h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_paymentsettings')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-3">
                <div class="card card-block">
                    <div class="list-group list-group-flush payment-setting-list-group">
                        <?php if(inicompute($payment_gateways)) {
                            $i = 0;
                            foreach ($payment_gateways as $payment_gateway) { ?>
                                <a data-toggle="pill" href="#payment-gateway<?=$payment_gateway->id?>" role="tab" class="list-group-item list-group-item-action <?=($i==0) ? 'active' : ''?>">
                                    <?=$payment_gateway->name?>
                                </a>
                        <?php $i++; } } ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="card card-block">
                    <div class="tab-content payment-setting-tab-contant">
                        <?php if(inicompute($payment_gateways)) {
                            $i = 0;
                            foreach ($payment_gateways as $payment_gateway) { ?>
                                <div class="tab-pane fade <?=($i==0) ? 'show active' : ''?>" id="payment-gateway<?=$payment_gateway->id?>" role="tabpanel">
                                    <form role="form" method="POST">
                                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                                        <fieldset class="setting-fieldset">
                                            <legend class="setting-legend"><?=$payment_gateway->name?> <?=$this->lang->line('paymentsettings_payment_setting')?></legend>
                                            <div class="row">
                                                <?php if(isset($payment_gateway_options[$payment_gateway->id]) && inicompute($payment_gateway_options[$payment_gateway->id])) {
                                                    $options = $payment_gateway_options[$payment_gateway->id];
                                                    foreach ($options as $option) {
                                                        $optionLang =  str_replace('_', ' ', $option->payment_option);
                                                        if($option->type == 'text') { ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group <?=form_error($option->payment_option) ? 'text-danger' : ''?>">
                                                                    <label class="control-label" for="<?=$option->payment_option?>"><?=$optionLang?> <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control <?=form_error($option->payment_option) ? 'is-invalid' : ''?>" id="<?=$option->payment_option?>" name="<?=$option->payment_option?>"  value="<?=set_value($option->payment_option, $option->payment_value)?>">
                                                                    <span ><?=form_error($option->payment_option)?></span>
                                                                </div>
                                                            </div>    
                                                    <?php } else if($option->type == 'select') { 
                                                        $activityArr = json_decode($option->activities, true); 
                                                        if(inicompute($activityArr)) { ?>
                                                            <div class="col-md-4">
                                                                <div class="form-group <?=form_error($option->payment_option) ? 'text-danger' : ''?>">
                                                                    <label class="control-label" for="<?=$option->payment_option?>"><?=$optionLang?> <span class="text-danger">*</span></label>
                                                                    <select class="form-control" name="<?=$option->payment_option?>" id="<?=$option->payment_option?>">
                                                                        <?php
                                                                            foreach($activityArr as $key => $select) {
                                                                                $optionSelected = '';
                                                                                if($option->payment_value == $key) {
                                                                                    $optionSelected = 'selected';
                                                                                }
                                                                                echo '<option value="'.$key.'" '.$optionSelected.'>'.$select.'</option>';
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    <span ><?=form_error($option->payment_option)?></span>
                                                                </div>
                                                            </div>
                                                <?php } } } } ?>
                                            </div>
                                        </fieldset>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('paymentsettings_update')?></button>
                                        </div>
                                    </form>
                                </div>
                        <?php $i++; } } ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

