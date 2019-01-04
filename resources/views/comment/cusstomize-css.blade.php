
@if(!empty($style_customize))
    <style>
        .alireview-verified {
            color: {{ !empty($style_customize['verify_color']) ? $style_customize['verify_color'] : config('settings')['style_customize']['verify_color'] }} {{ "!important" }};
        }
        .alireview-number-total-review {
            border-radius: {{ !empty($settings['rating_point']) && $settings['rating_point'] === "1" ?  config('settings.style_customize.border_radius') : "50%" }} {{ "!important" }};
            background: {{ !empty($style_customize['box_color']) ? $style_customize['box_color'] : config('settings')['style_customize']['box_color'] }} {{ "!important" }};
        }

        .alr-icon-star:before {
            content: "{{ !empty($settings['rating_card']) ? config('settings.style_customize.icon_rating.'.$settings['rating_card']) : config('settings.style_customize.icon_rating.1')}}" {{ "!important" }};
        }
        
        .alr-count-reviews .alr-star i,
        .alireview-total-review .rating-symbol-foreground,
        #alireview-review-widget-badge .rating-symbol-foreground > span,
        .arv-collection .rating-symbol-foreground > span,
        .alireview-status .rating-symbol-foreground > span {
            color: {{ !empty($style_customize['icon_color']) ? $style_customize['icon_color'] : config('settings')['style_customize']['icon_color'] }} {{ "!important" }};
        }

        .alr-count-reviews .alr-progress-bar-wrap .alr-progress-bar div {
            background-color: {{ !empty($style_customize['icon_color']) ? $style_customize['icon_color'] : config('settings')['style_customize']['icon_color'] }} {{ "!important" }};
        }

        .alireview-avatar {
            border-radius: {{ !empty($style_customize['avatar']) && $style_customize['avatar'] === "square" ?  config('settings.style_customize.border_radius') : "50%" }} {{ "!important" }};
        }
        @if(!empty($code_css))
            {!! $code_css !!}
        @endIf
    </style>
@endIf
