<?php
class StripeRule {
    public function rule(): array
    {
        return [
            [
                'field' => 'stripe_key',
                'label' => 'stripe key',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'stripe_secret',
                'label' => 'stripe secret',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'stripe_status',
                'label' => 'status',
                'rules' => 'trim|required',
            ]
        ];
    }
}

?>