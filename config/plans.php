<?php
return [
	'free' => [
		'price' => 0,
		'name' => 'free',
		'title' => 'Free',
		'styles' => [1,2,3],
		'custom_style' => false,
		'rating_point' => [1],
		'rating_card' => [1],
		'total_product' => 10,
		'total_reviews_product' => false,
		'total_reviews_publish_product' => 5,
		'code_power' =>  true,
		'pin' => true,
		'except_keyword' => false,
		'reviews_page' => false,
		'sort_reviews' => ['sort_by_date','sort_by_social'],
		'sample_reviews' => 0,
		'add_from_operlo' => false,
		'next_plan' => 'premium',
	],
	'premium' => [
		'price' => 9,
		'name'=>'premium',
		'title'=>'Pro',
		'styles' => [1,2,3,4,5,6,7],
		'custom_style' => true,
		'rating_point' => [1,2,3,4,5],
		'rating_card' => [1,2],
		'total_product' => false,
		'total_reviews_product' => false,
		'total_reviews_publish_product' => false,
		'code_power' => false,
		'pin' => true,
		'except_keyword' => true,
		'reviews_page' => true,
		'sort_reviews' => ['sort_by_date','sort_by_social'],
		'sample_reviews' => 20,
		'add_from_operlo' => false,
        'next_plan' => 'unlimited',
    ],
	'unlimited' => [
		'price' => 14.9,
		'name'=>'unlimited',
		'title'=>'Unlimited',
		'styles' => [1,2,3,4,5,6,7],
		'custom_style' => true,
		'rating_point' => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17],
		'rating_card' => [1,2,3,4,5,6,7,8],
		'total_product' => false,
		'total_reviews_product' => false,
		'total_reviews_publish_product' => false,
		'code_power' => false,
		'pin' => true,
		'except_keyword' => true,
		'reviews_page' => true,
		'sort_reviews' => ['sort_by_date','sort_by_social'],
		'sample_reviews' => 2000,
		'add_from_operlo' => true,
        'next_plan' => '',
    ],
	'alias' => [
		'free' => 'FREE',
		'premium' => 'PRO',
		'unlimited' => 'UNLIMITED'
	]
];