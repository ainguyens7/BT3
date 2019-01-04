<?php
return [
    // will expired after 1 week
    'default_expire_cache' =>  env('DEFAULT_CACHE_FRONTEND_EXPIRED', 604800),
    'key_hash_cache_frontend' => 'hash_cache_frontend',
    'key_field_hash_pagination' => 'field_hash_pagination',
    'key_field_hash_avg_star' => 'field_hash_avg_star',
    'key_field_hash_total_star' => 'field_hash_total_star',
    'key_field_hash_total_status' => 'field_hash_total_status',
    'key_hash_cache_review_page_frontend' => 'hash_cache_review_page_frontend',
];