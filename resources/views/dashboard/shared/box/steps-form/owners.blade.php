<?php /** @var \Gcr\Process $process */ ?>
@if (!$process->isUpdating() || $process->isEditingOwners() || $process->isEditingCapital() || $process->isEditingTransferToAnotherUf() || $process->isEditingTransferFromAnotherUfToSp())
    <h3>{{ $step['label'] }}</h3>
    <section>
        @php
            $owners = $process->owners
        @endphp

        <template id="new-owner">
            @component('dashboard.shared.box.steps-form.partials.owner', [
                'key' => false,
                'owner' => false,
                'step' => $step,
                'process' => $process
            ])
            @endcomponent
        </template>

        <div
            class="panel-group"
            id="owners"
            role="tablist"
            aria-multiselectable="true"
            data-last-id="{{ $owners->count() }}">
            @foreach ($owners as $key => $owner)
                @php
                    $key++
                @endphp

                @component('dashboard.shared.box.steps-form.partials.owner', compact('key', 'process', 'owner', 'step'))
                @endcomponent
            @endforeach
            @if ($process->isBusinessman() && $owners->count() === 0)
                @component('dashboard.shared.box.steps-form.partials.owner', [
                    'key' => 1,
                    'owner' => false,
                    'step' => $step,
                    'process' => $process
                ])
                @endcomponent
            @endif
        </div>

        @if (($process->isTransformation() && !$process->isTransformationBusinessman()) || !$process->isBusinessman())
            <div class="box-add-new-owner">
                <button type="button" class="btn btn-lg btn-primary" data-button-add-new-owner>
                    <i class="fa fa-plus"></i>
                    Adicionar {{ $step['label'] }}
                </button>
            </div>
        @endif
    </section>

@endif
