@if ($paginator->hasPages())
    <div class="list-review-pagination">
        <ul class="alireview-pagination">
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="alireview-active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="#">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>
    </div>
@endif


<?php
// config
$link_limit = 5; // maximum number of links (a little bit inaccurate, but will be ok for now)
$is_add_dot = TRUE;
?>
@if ($paginator->lastPage() > 1)
    <div class="list-review-pagination list-review-pagination-mobile" style="display: none">
        <ul class="alireview-pagination">
            <li class="{{ ($paginator->currentPage() == 1) ? ' alireview-active' : '' }}">
                <a href="{{ $paginator->url(1) }}">{{ 1 }}</a>
            </li>
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
				<?php
				$half_total_links = floor( $link_limit / 2 );
				$from = $paginator->currentPage() - $half_total_links;
				$to = $paginator->currentPage() + $half_total_links;
				if ( $paginator->currentPage() < $half_total_links ) {
					$to += $half_total_links - $paginator->currentPage();
				}
				if ( $paginator->lastPage() - $paginator->currentPage() < $half_total_links ) {
					$from -= $half_total_links - ( $paginator->lastPage() - $paginator->currentPage() ) - 1;
				}
				?>
                @if ($from < $i && $i < $to && $i !=1 && $i != $paginator->lastPage())
                    @if($from > 1 && $is_add_dot == TRUE)
                        <li class="disabled get-one"><span>...</span></li>
						<?php $is_add_dot = FALSE ?>
                    @endif

                    <li class="{{ ($paginator->currentPage() == $i) ? ' alireview-active' : '' }}">
                        <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor
            @if($paginator->lastPage() > $to)
                <li class="disabled"><span>...</span></li>
            @endif
            <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' alireview-active' : '' }}">
                <a href="{{ $paginator->url($paginator->lastPage()) }}">{{$paginator->lastPage()}}</a>
            </li>
        </ul>
    </div>
@endif
