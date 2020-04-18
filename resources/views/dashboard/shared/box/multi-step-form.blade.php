@component('dashboard.shared.box')
    @slot('title')
        {{ $title }}
    @endslot
    @slot('body')
        <div class="box-alerts"></div>
        <div id="alert-template" class="hidden">
            <div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <ul></ul>
            </div>
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
