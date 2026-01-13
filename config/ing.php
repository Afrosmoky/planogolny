<?php
return [
    'base_url' => env('ING_API_BASE_URL'),
    'client_id' => env('ING_CLIENT_ID'),
    'client_secret' => env('ING_CLIENT_SECRET'),
    'api_key' => env('ING_API_KEY'),
    'account_number' => env('ING_ACCOUNT_NUMBER'),
    'gross' => env('ING_RAPORT_GROSS'),
    'company_type' => env('ING_COMPANY_TYPE', 'B2C'),
    'ing_invoicing' => env('FEATURE_ING_INVOICING', false),
    'env' => env('ING_ENV', 'production'),
];
