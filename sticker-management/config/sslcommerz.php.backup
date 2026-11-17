<?php

return [
    'projectPath' => env('PROJECT_PATH', 'http://127.0.0.1:8000/'),
    // For Live, use "https://securepay.sslcommerz.com"
    // For demo, use "https://sandbox.sslcommerz.com"
    'apiDomain' => env('SSLCZ_API_DOMAIN', 'https://sandbox.sslcommerz.com'),
    'apiCredentials' => [
        'store_id' => env('SSLCZ_STORE_ID', 'testbox'),
        'store_password' => env('SSLCZ_STORE_PASSWD', 'qwerty'),
    ],
    'apiUrl' => [
        'make_payment' => '/gwprocess/v4/api.php',
        'transaction_status' => '/validator/api/merchantTransIDvalidationAPI.php',
        'order_validate' => '/validator/api/validationserverAPI.php',
        'refund_payment' => '/validator/api/merchantTransIDvalidationAPI.php',
        'refund_status' => '/validator/api/merchantTransIDvalidationAPI.php',
    ],
    'connect_from_localhost' => filter_var(env('IS_LOCALHOST', false), FILTER_VALIDATE_BOOLEAN), // For Sandbox, use "true", For Live, use "false"
    'success_url' => '/subscriber/sslcommerz/success',
    'failed_url' => '/subscriber/sslcommerz/fail',
    'cancel_url' => '/subscriber/sslcommerz/cancel',
    'ipn_url' => '/subscriber/sslcommerz/ipn',
];
