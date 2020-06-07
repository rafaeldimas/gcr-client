<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
        @if(isset($titleAddon))
            {{ $titleAddon }}
        @endif
    </div>
    <div class="box-body">
        {{ $body }}
    </div>
    <div class="box-footer">
        {{ $footer }}
    </div>
</div>
