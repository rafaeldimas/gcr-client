<?php /** @var Gcr\Process $process */ ?>

@php
    $documents = $process->documents;
@endphp

<h3>{{ $step['label'] }}</h3>
<section>
    @foreach(Gcr\Document::attributeOptions('type') as $type => $label)
        @php
            $document = $documents->where('type', $type)->first()
        @endphp

        @if (Gcr\Document::TYPE_IPTU !== $type || $process->showViability())
            <div class="media">
                <div class="media-left media-middle">
                    @if ($document && $document->showIptu())
                        <a href="{{ $document->getRouteFile() }}">
                            <img class="media-object" src="{{ $document->getRouteFile() }}" alt="{{ $label }}">
                        </a>
                    @endif
                </div>
                <div class="media-body">
                    <div class="form-group">
                            <label for="documents[{{ $type }}][file]">{{ $label }}</label>
                            <input
                                type="hidden"
                                name="documents[{{ $type }}][id]"
                                value="{{ !$document ? '' : $document->id }}">
                            <input
                                id="documents[{{ $type }}][file]{{ Gcr\Document::TYPE_OTHER === $type ? '[]' : '' }}"
                                name="documents[{{ $type }}][file]{{ Gcr\Document::TYPE_OTHER === $type ? '[]' : '' }}"
                                type="file"
                                {{ Gcr\Document::TYPE_OTHER === $type ? 'multiple' : '' }}>
                            @if($document)
                                <span id="files[{{ $type }}]" class="help-block">Arquivo ja enviado, para substituir selecione um novo arquivo.</span>
                            @endif
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</section>
