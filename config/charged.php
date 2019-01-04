<?php
return [
    'name' => 'Full options with Premium Package only 9$',
    'price' => 9,
    'trial_days' => 7,
    'time_trial_link' => 2, //ngày hết hạn của trial link
    'test' => ((env('APP_ENV') == 'local') ? true :  null),
    'default_trial_days' => 1,
    'app_friendly' => 'affiliate',
];