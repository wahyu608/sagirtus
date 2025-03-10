@props(['links'])

<div class="flex mt-8 space-x-3">
    @foreach ($links as $link)
        @if ($link['label'] === 'Next &raquo;' && $link['url'] === null)
            @continue
        @endif
        <a 
            href="{{ $link['url'] ?? '#' }}" 
            class="{{ $link['active'] 
                ? 'relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md'
                : 'relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-blue-500 hover:text-white' }}"
            {!! $link['url'] ? '' : 'aria-disabled="true"' !!}
        >
            {!! $link['label'] !!}
        </a>
    @endforeach
</div>
