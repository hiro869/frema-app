@if ($paginator->hasPages())
    <nav class="custom-pagination" role="navigation" aria-label="Pagination Navigation">
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="custom-page disabled">前へ</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="custom-page">前へ</a>
        @endif

        {{-- Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="custom-page disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="custom-page active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="custom-page">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="custom-page">次へ</a>
        @else
            <span class="custom-page disabled">次へ</span>
        @endif
    </nav>
@endif
