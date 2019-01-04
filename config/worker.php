<?php

return [
    'import_product' => [
        'type' => 'product.import',
    ],

    'add_product_metafield' => [
        'type' => 'add.product.metafield',
    ],
    'delete_product_metafield' => [
        'type' => 'delete.product.metafield',
    ],
    'inject_asset' => [
        'type' => 'inject.asset'
    ],

    'inject_template_load_asset' => [
        'type' => 'inject.template.load.asset'
    ],

    'inject_stylesheet' => [
        'type' => 'inject.stylesheet'
    ],

    'inject_script' => [
        'type' => 'inject.script'
    ],

    'inject_inline_style' => [
        'type' => 'inject.inline.style'
    ],

    'add_widget_review_box' => [
        'type' => 'widget.reviewbox.add',
    ],

    'delete_widget_review_box' => [
        'type' => 'widget.review.delete',
    ],

    'add_review_badge' => [
        'type' => 'widget.badge.add',
    ],

    'delete_review_badge' => [
        'type' => 'widget.badge.delete',
    ],

    'add_script_tag' => [
        'type' => 'add.script.tag',
    ],

    'inject_review_box' => [
        'type' => 'asset.inject.review_box',
    ],

    'inject_review_badge' => [
        'type' => 'asset.inject.review_badge',
    ],
    
    'worker_host' => env('WORKER_HOST', 'http://127.0.0.1'),
    'worker_port' => env('WORKER_PORT', 3000)
];
