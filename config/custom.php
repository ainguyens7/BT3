<?php
/**
 * Custom var
 */
return [
    'app_version' => 'v2',
    'status' => [
        'All Status'  => '',
        'published'   => 1,
        'unpublished' => 0,
    ],
    'paginate_front_end' => 5,
    'paginate' => [
        '10'  => '10',
        '50'  => '50',
        '100' => '100',
        '0'   => 'All'
    ],
    'star' => [
        5,4,3,2,1
    ],
    'style_review' => 1,
    'setting_default' => [
//        'get_only_picture' => 1,
//        'get_only_star'    => [4,5],
        'get_max_number_review' => 50,
        'section_show' => [
            'country',
            'image',
            'date_time',
            'avatar'
        ]
    ],
    'list_style_review' => [
        [
            'key'        => 1,
            'thumb'      => '/images/screen_style1.png',
            'name'       => 'Square',
            'part_theme' => ''
        ],
        [
            'key'        => 2,
            'thumb'      => '/images/screen_style2.png',
            'name'       => 'Circle',
            'part_theme' => ''
        ],
        [
            'key'        => 3,
            'thumb'      => '/images/screen_style3.png',
            'name'       => 'Masonry',
            'part_theme' => ''
        ]
    ],
    'TIME_SECONDS' => [
        'MINUTE_IN_SECONDS' => 60,
        'HOUR_IN_SECONDS'   => 60*60,
        'DAY_IN_SECONDS'    => 24*60*60,
        'WEEK_IN_SECONDS'   => 7*24*60*60,
        'MONTH_IN_SECONDS'  => 30*24*60*60,
        'YEAR_IN_SECONDS'   => 365*24*60*60,
    ],
    'country_code' => [
        'AF' => 'Afghanistan',
        'AX' => 'Åland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan'
    ]
];
