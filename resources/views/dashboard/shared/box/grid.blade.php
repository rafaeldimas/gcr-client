@component('dashboard.shared.box')
    @slot('title')
        {{ $boxTitle }}
    @endslot
    @slot('body')
        @component('dashboard.shared.grid')
            @slot('head')
                {{ $gridHead}}
            @endslot
            @slot('body')
                {{ $gridBody}}
            @endslot
        @endcomponent
    @endslot
    @slot('footer')
        {{ $boxFooter}}
    @endslot
@endcomponent
