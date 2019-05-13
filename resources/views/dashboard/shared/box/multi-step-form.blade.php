@component('dashboard.shared.box')
    @slot('title')
        {{ $title }}
    @endslot
    @slot('body')
        <div class="alert alert-danger hidden">
            <ul></ul>
        </div>
        <form data-form-process action="{{ route('dashboard.process.update', [$process]) }}" enctype='multipart/form-data'>
            @method('PUT')
            @foreach($steps as $type => $step)
                @component("dashboard.shared.box.steps-form.{$type}", compact('step', 'process'))
                @endcomponent
            @endforeach
        </form>
    @endslot
    @slot('footer')
    @endslot
@endcomponent
