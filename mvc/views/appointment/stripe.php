<div id="mainStripe" data-stripe-key="<?=$payment_options['stripe_key']?>">
    <div class="form-group">
        <label><?=$this->lang->line('appointment_card_number')?> <span class="text-danger">*</span> </label>
        <div id="card_number" class="form-control"></div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label><?=$this->lang->line('appointment_expiry_date')?> <span class="text-danger">*</span> </label>
                <div id="card_expiry" class="form-control"></div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><?=$this->lang->line('appointment_cvc_code')?> <span class="text-danger">*</span> </label>
                <div id="card_cvc" class="form-control"></div>
            </div>
        </div>
        <div class="col-sm-12 text-danger" id="paymentResponse"></div>
    </div>
</div>