@if($totalPage > 1)
<div class="pagination-wrap">
    <ul class="pagination-list pull-left">
        <li>
            <span class="page-number page-prev" href=""><i class="demo-icon icon-left-open-2"></i></span>
        </li>
        @for($i = 1; $i <= $totalPage; $i++)
        <li>
            @php
                $filterPagination['paged'] = $i;
                $urlParts = http_build_query($filterPagination);
                $urlPagination = env('APP_URL').'/?'.$urlParts;
            @endphp
            <a href="{!! $urlPagination !!}">
                <span class="{{ ($i == $currentPage) ? 'page-current' : '' }} page-number">{{ $i }}</span>
            </a>
        </li>
        @endfor
        <li>
            <a class="page-number page-next" href=""><i class="demo-icon icon-right-open-2"></i></a>
        </li>
    </ul>
    <p class="pagination-show-text hidden-xs hidden-sm pull-right">
        Showing <span>10</span> of 135 reviews
    </p>
</div>
@endif