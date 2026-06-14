@if ($paginator->hasPages())
    <nav class="flex items-center gap-1">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg bg-stone-100 text-stone-300 text-sm cursor-not-allowed">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-500 hover:text-stone-800 text-sm transition-colors">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1.5 rounded-lg bg-stone-100 text-stone-400 text-sm">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-500 hover:text-stone-800 text-sm transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-500 hover:text-stone-800 text-sm transition-colors">›</a>
        @else
            <span class="px-3 py-1.5 rounded-lg bg-stone-100 text-stone-300 text-sm cursor-not-allowed">›</span>
        @endif
    </nav>
@endif