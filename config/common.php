<?php
return [
    'app_version' => 'v4.1',
    'pagination' => 10,
    'paginate_front_end' => 5,
    'status' => [
        'unpublish' => 0,
        'publish' => 1
    ],
    'is_reviews_app' => [
        'reviews' => 1,
        'no_reviews' => 0
    ],
    'is_reviews' => [
        'reviews' => 1,
        'no_reviews' => -1
    ],
	'api_limit' => 100,
	'ftp_host' => env('APP_ENV') === 'production' ?'imgs.fireapps.vn' : '54.179.157.236',
	'ftp_user' => 'imgs',
    'ftp_pass' => 'Yatb0DQNpHEl',
    'review_sources' => [
        'aliexpress' => 'AliExpress',
        'aliorder'=>"AliOrders",
        'oberlo' => 'Oberlo',
        'web' => 'Customers',
        'default' => 'Default'
    ],
    // use for data layer
    'publish_review_number' => [
        'default_source' => 20,
        'aliexpress_source' => 5
    ]
];
