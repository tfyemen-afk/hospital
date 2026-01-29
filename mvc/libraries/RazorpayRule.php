
<?php
class RazorpayRule
{
    public function rule(): array
    {
        return [
            [
                'field' => 'razorpay_key',
                'label' => 'razorpay key',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'razorpay_secret',
                'label' => 'razorpay secret',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'razorpay_status',
                'label' => 'status',
                'rules' => 'trim|required',
            ]
        ];
    }
}