@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="btn btn-secondary" style="opacity: 0.6; cursor: not-allowed;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-secondary" rel="prev">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Anterior
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div style="display: flex; gap: 0.25rem;">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="btn btn-secondary" style="cursor: default;">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="btn btn-primary" style="cursor: default;">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="btn btn-secondary">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-secondary" rel="next">
                    Siguiente
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @else
                <span class="btn btn-secondary" style="opacity: 0.6; cursor: not-allowed;">
                    Siguiente
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            @endif
        </div>

        {{-- Información de la página --}}
        <div style="text-align: center; margin-top: 1rem; color: var(--color-text-medium); font-size: 0.875rem;">
            <p>
                Mostrando 
                <strong>{{ $paginator->firstItem() ?? 0 }}</strong> 
                a 
                <strong>{{ $paginator->lastItem() ?? 0 }}</strong> 
                de 
                <strong>{{ $paginator->total() }}</strong> 
                resultados
            </p>
        </div>
    </nav>
@endif