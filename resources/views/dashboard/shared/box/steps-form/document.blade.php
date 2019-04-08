<?php /** @var Gcr\Process $process */ ?>
<h3>{{ $step['label'] }}</h3>
<section>
    @foreach(['rg', 'cpf', 'iptu'] as $type)
        <div class="media">
            <div class="media-left media-middle">
                @if($document = $process->document()->where('type', $type)->first())
                <a href="{{ route('documents', [ $document->file ]) }}">
                    <img class="media-object" src="{{ route('documents', [ $document->file ]) }}" alt="{{ $type }}">
                </a>
                @endif
            </div>
            <div class="media-body">
                <div class="form-group">
                    <label for="document[{{ $type }}]">{{ strtoupper($type) }}</label>
                    <input type="hidden" name="document[{{ $type }}][id]" value="{{ !$document ?: $document->id }}">
                    <input id="document[{{ $type }}]" name="document[{{ $type }}][file]" type="file">
                    @if($document)
                    <span id="files[{{ $type }}]" class="help-block">Arquivo ja enviado, para substituir selecione um novo arquivo.</span>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</section>
