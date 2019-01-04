<li>
    <p class="point">

        <span class="fz13 color-grey-800 fw600">
            {{($rating_title == 1) ?  $rating_title .' ' .  __('reviews.text_star') : $rating_title .' ' .  __('reviews.text_stars')}}
        </span>
        {{-- <i class="material-icons">star_rate</i> --}}
    </p>
    <div>
        <div class="progress">
            <div class="progress-bar progress-warning" role="progressbar" aria-valuenow="{{ $rating_base_value }}" aria-valuemin="0"
                aria-valuemax="{{ $rating_total_value }}" style="width: {{ !empty($rating_total_value ) ? $rating_base_value /  $rating_total_value * 100 :0 }}%"></div>
        </div>
    </div>
    <span class="fz13 color-grey-800 text-right">({{ $rating_base_value }})</span>
</li>