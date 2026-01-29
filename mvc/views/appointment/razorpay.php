<?php
$productinfo       = $appointment->pcase;
$txnid             = time();
$surl              = $surl;
$furl              = $furl;
$key_id            = $payment_gateway_option['razorpay_key'];
$currency_code     = ucwords($generalsettings->currency_code);
$total             = ($appointment->amount * 100);
$amount            = $appointment->amount;
$merchant_order_id = $appointment->appointmentID;
$card_holder_name  = $user->name;
$email             = $user->email;
$phone             = $user->phone;
$siteName          = $generalsettings->system_name;
$return_url        = base_url('razorpay/callback');
?>

<form name="razorpay-form" id="razorpay-form" action="<?=$return_url?>" method="POST">
  <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
  <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" />
  <input type="hidden" name="merchant_order_id" id="merchant_order_id" value="<?=$merchant_order_id?>"/>
  <input type="hidden" name="merchant_trans_id" id="merchant_trans_id" value="<?=$txnid?>"/>
  <input type="hidden" name="merchant_product_info_id" id="merchant_product_info_id" value="<?=$productinfo?>"/>
  <input type="hidden" name="merchant_surl_id" id="merchant_surl_id" value="<?=$surl?>"/>
  <input type="hidden" name="merchant_furl_id" id="merchant_furl_id" value="<?=$furl?>"/>
  <input type="hidden" name="card_holder_name_id" id="card_holder_name_id" value="<?=$card_holder_name?>"/>
  <input type="hidden" name="merchant_total" id="merchant_total" value="<?=$total?>"/>
  <input type="hidden" name="merchant_amount" id="merchant_amount" value="<?=$amount?>"/>
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  var razorpay_options = {
    key: "<?=$key_id?>",
    amount: "<?=$total?>",
    name: "<?=$siteName?>",
    description: "Order # <?=$merchant_order_id?>",
    netbanking: true,
    currency: "<?=$currency_code?>",
    prefill: {
      name:"<?=$card_holder_name?>",
      email: "<?=$email?>",
      contact: "<?=$phone?>"
    },
    notes: {
      soolegal_order_id: "<?=$merchant_order_id?>",
    },
    handler: function (transaction) {
        document.getElementById('razorpay_payment_id').value = transaction.razorpay_payment_id;
        document.getElementById('razorpay-form').submit();
    },
    "modal": {
        "ondismiss": function(){
            location.reload()
        }
    }
  };

  var razorpay_submit_btn, razorpay_instance;

  if(typeof Razorpay == 'undefined'){
      setTimeout(razorpaySubmit, 200);
      if(!razorpay_submit_btn && el){
        razorpay_submit_btn = el;
        el.disabled = true;
        el.value = 'Please wait...';
      }
    } else {
      if(!razorpay_instance){
        razorpay_instance = new Razorpay(razorpay_options);
        if(razorpay_submit_btn){
          razorpay_submit_btn.disabled = false;
          razorpay_submit_btn.value = "Pay Now";
        }
      }
      razorpay_instance.open();
    }
</script>