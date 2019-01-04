<div class="alr-summary">
    <div class="alr-wrap-count">
        <ul class="alr-count-reviews">
            @for($i = 5; $i >= 1; $i--)
                <li star="{{ $i }}">
                    <div class="alr-sum-wrap">
                        <span class="alr-sum-point">{{ $i }}</span>
                        <span class="alr-star"><i class="alr-icon-star" style="color: #FFB303;"></i></span>
                    </div>
                    <div class="alr-progress-bar-wrap">
                        <div class="alr-progress-bar">
                            <div style="max-width: {{ !empty($statistic['total_star']) ? $statistic['total_star_'.$i] /  $statistic['total_star'] * 100 :0 }}%"></div>
                        </div>
                    </div>
                    <span class="alr-count">({{$statistic['total_star_'.$i]}})</span>
                </li>
            @endfor
        </ul>
    </div>
</div>