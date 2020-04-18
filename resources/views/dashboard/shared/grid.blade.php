@if(request()->routeIs('dashboard.process.index'))
    @component('dashboard.shared.search')
    @endcomponent
@endif
<table class="table table-bordered">
    <thead>
        {{ $head }}
    </thead>
    <tbody>
        {{ $body }}
    </tbody>
</table>
