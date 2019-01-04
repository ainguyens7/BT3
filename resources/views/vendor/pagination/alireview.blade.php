@if ($paginator->hasPages())
    <nav>
        <ul class="pagination ali-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span class="page-number page-prev"><i class="material-icons">chevron_left</i></span></li>
            @else
                <li><a class="page-number page-prev" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="material-icons">chevron_left</i></a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="page-current page-number ">{{ $page }}</span></li>
                        @else
                            <li><a class="page-number" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a class="page-number page-next" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="material-icons">chevron_right</i></a></li>
            @else
                <li><span class="page-number page-next"><i class="material-icons">chevron_right</i></span></li>
            @endif
        </ul>
    </nav>
@endif
