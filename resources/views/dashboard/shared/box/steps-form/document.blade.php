<?php /** @var Gcr\Process $process */ ?>

@php
    $documents = $process->documents;
@endphp

<h3>{{ $step['label'] }}</h3>
<section>
    @foreach(Gcr\Document::attributeOptions('type') as $type => $label)
        @php
            /** @var \Gcr\Document[]|\Illuminate\Database\Eloquent\Collection $docs */
            $docs = $documents->where('type', $type);

            /** @var \Gcr\Document $document */
            $document = $documents->where('type', $type)->first();
        @endphp

        @if (Gcr\Document::TYPE_IPTU !== $type || $process->showViability())
            <div class="media">
                <div class="media-left media-middle">
                    @php($key = 0)
                    @foreach($docs as $doc)
                        @if ($doc->showIptu())
                            <a href="{{ $doc->getRouteFile() }}" style="white-space: nowrap;">
                                @if ($doc->isOther())
                                    {{ ++$key }} -
                                @endif
                                {{ $label }}
                            </a>
                            <br>
                        @endif
                    @endforeach
                </div>
                <div class="media-body">
                    <div class="form-group">
                        <label for="documents[{{ $type }}][file]">
                            {{ $label }}
                            @if($document && $document->isOther())
                                (Selecione todos os arquivo de uma única vez)
                            @endif
                        </label>
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
