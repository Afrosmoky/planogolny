<?php
return [
    'client_id' => env('TPAY_CLIENT_ID'),
    'client_secret' => env('TPAY_CLIENT_SECRET'),
    'base_url' => env('TPAY_BASE_URL'),
    'env' => env('TPAY_ENV', 'production'),
];
