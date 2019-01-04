<?php
/**
 * Set event on/off
 * Current only apply for event flash deal
 */

return [
    'shouldEnableEventFlashDeal' => true,
    'defaultDayDisplayPopupForFree' => 10,
    'flashDealAvaiableFor' => '2 day',
    'discountCode'  => 'FAST15',
    'discountValue' => 15,
    'discountMsg'   => 'DISCOUNT 15%',
    'jumpDay'          => 3,
    'popup' => [
        [
            'comment'   => 'Popup remove branch',
            'resource'  => 'images/backend/popup_remove_power.png',
            'popup_key' => 0,
            'modal_id'  => 'modal-popup-remove-power',
            'close_id'  => 'button-close-modal-popup-remove-power'
        ],
        [
            'comment'   => 'Popup unlimted reviews',
            'resource'  => 'images/backend/popup_upgrade_to_pro_1.png',
            'popup_key' => 1,
            'modal_id'  => 'modal-popup-upgrade-to-pro-1',
            'close_id'  => 'button-close-modal-popup-upgrade-to-pro-1'
        ],
        [
            'comment'   => 'Popup integrate with oberlo',
            'resource'  => 'images/backend/popup_upgrade_to_pro_2.png',
            'popup_key' => 2,
            'modal_id'  => 'modal-popup-upgrade-to-pro-2',
            'close_id'  => 'button-close-modal-popup-upgrade-to-pro-2'
        ]
    ]
];