@if ($paginator->hasPages())
<div class="glass-effect-subtle rounded-xl shadow-lg px-4 py-2 flex items-center justify-center space-x-1">
    @if ($paginator->onFirstPage())
        <span class="px-4 py-2 rounded-full bg-slate-700 text-slate-400 cursor-not-allowed transition">Previous</span>
    @else
        <a class="px-4 py-2 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold shadow hover:from-blue-600 hover:to-blue-700 transition" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-4 py-2 rounded-full bg-slate-700 text-slate-400 cursor-not-allowed transition">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-4 py-2 rounded-full bg-gradient-to-r from-orange-400 to-yellow-300 text-slate-900 font-bold shadow transition">{{ $page }}</span>
                @else
                    <a class="px-4 py-2 rounded-full bg-slate-800 text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white font-semibold transition" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a class="px-4 py-2 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold shadow hover:from-blue-600 hover:to-blue-700 transition" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
    @else
        <span class="px-4 py-2 rounded-full bg-slate-700 text-slate-400 cursor-not-allowed transition">Next</span>
    @endif
</div>
@endif
