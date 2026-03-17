@push('head')
    @foreach ($page['externalStyles'] as $href)
        <link rel="stylesheet" href="{{ $href }}">
    @endforeach

    @foreach ($page['styles'] as $style)
        <link rel="stylesheet" href="{{ asset($style) }}">
    @endforeach
@endpush

@push('scripts')
    @foreach ($page['scripts'] as $script)
        <script src="{{ asset($script) }}" defer></script>
    @endforeach
@endpush

<x-app-layout :body-class="$page['bodyClass']">
    @include($page['view'])
</x-app-layout>
