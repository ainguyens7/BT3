<?php
/**
 * Shop settings config
 */
return [
    'style'          => 5,
    'setting'        => [
        'get_only_picture'      => [true],
        'get_only_content'      => [true],
        'get_only_star'         => [ "4", "5" ],
        'get_review_without_picture' => false, // added in v3.3
        'customize_customer' => false, // added in v3.3
        'get_max_number_review' => 50,
        'section_show'          => [
            'country',
            'image',
            'date_time',
            'avatar',
            'hide_name'
        ],
        'max_number_per_page'   => 8,
        'sort_reviews'          => 'sort_by_social',
        'country_get_review'    => \App\Helpers\Helpers::getDefaultCountryCode(),
        'except_keyword' => '',
        'translate_reviews' => 1,
        'display_rate_list'=>1,
        'active_date_format'=>1,
        'date_format'  =>'d/m/Y',
        'affiliate_program'=>'none',
        'affiliate_aliexpress'=>'',
        'affiliate_admitad'=>'',
        'language_translate_reviews'=>'en',
	    'is_local_name' => 1,
	    'male_name_percent' => '50',
	    'female_name_percent' => '50',
       'approve_review_stars'=> [4,5],
    ],
    'is_translate' => '1',
    'translate'      => [
        'title'                      => 'Customer reviews',
        'button_add'                 => 'WRITE A REVIEW',
        'text_total'                 => 'Based on {reviews} review',
        'text_total_multi'           => 'Based on {reviews} reviews',
        'text_verified'              => 'Verified',
        'text_ago'                   => 'ago',
        'title_form'                 => 'Add Your Review',
        'label_name'                 => 'Your name',
        'label_email'                => 'Your email',
        'label_your_rating'          => 'Your rating',
        'label_image'                => '+ Photo',
        'label_placeholder'          => 'Tooltip text',
        'label_title'                => 'Title',
        'label_notice'               => 'Notice',
        'label_description'          => 'Description',
        'label_submit_button'        => 'Submit button',
        'label_send_button'          => 'Submit button',
        'label_name_edit'            => 'Name Edit',
        'label_email_edit'           => 'Email Edit',
        'label_footer'               => 'Footer',
        'label_header'               => 'Header',
        'label_live_review'          => 'Live Preview',
        'button_submit'              => 'SUBMIT REVIEW',
        'choose_file'                => 'Choose File',
        'optional'                   => '',
        'error_maximum_characters'   => 'This value should have {number} characters maximum',
        'error_required'             => 'This field is required',
        'error_email'                => 'Invalid email format',
        'message_thanks'             => 'Thanks message',
        'message_thanks_has_approve' => 'Thank you for your time and interest in providing us feedback.',
        'error_add_fail' => 'Error! Please try again or contact admin for help.',
        'min' => 'min',
        'mins' => 'mins',
        'hour' => 'hour',
        'hours' => 'hours',
        'day' => 'day',
        'days' => 'days',
        'week' => 'week',
        'weeks' => 'weeks',
        'month' => 'month',
        'months' => 'months',
        'year' => 'year',
        'years' => 'years',
        'text_write_comment' => 'Write your comment here 2000 characters left',
        'text_reviews_title' => 'reviews',
        'text_review_title' => 'review',
        'text_empty_review' => 'Be the first to review this product',
    ],
    'is_comment_default' => 0,
    'rand_comment_default' => [
        'from' => 5,
        'to' => 10,
    ],
    'approve_review' => 0,
    'style_customize' => [
        'form_title' =>'#32373d', // color of title form
        'star_summary' =>'#faae0b', // color of star
        'summary_background' =>'#32373d', // background color off summary
        'summary_text' =>'#ffffff', // color text off summary
        'button_background' =>'#32373d', // background of button
        'button_text' => '#fff', // color text of button
        'notify_success_background' => '#dff0d8', // background of notify
        'notify_success_text' => '#3c763d', // color text of notify
        //'text_color' => 'rgba(0,0,0,.85)',
        'box_color'=>'#242539',
        'verify_color'=>'#4AD991',
        'icon_color'=>'#FFB303',
        'background_color'=>'#4AD991',
        'avatar'=>'circle',
        'border_radius' => '5px',
        'icon_rating' => [
            '1' => '\E901',
            '2' => '\E904',
            '3' => '\E905',
            '4' => '\E906',
            '5' => '\E900'
        ]
    ],
    'code_css' => '',
    'rating_card' => '1',// icon star
    'rating_point' => '1',
    'active_frontend' => '1',

];