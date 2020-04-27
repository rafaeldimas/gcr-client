<?php /** @var \Gcr\Process $process */ ?>
@if ($process->isUpdating() && $process->isEditingSubsidiary())
    <h3>{{ $step['label'] }}</h3>
    <section>
        @php
            /** @var \Gcr\Subsidiary[]|\Illuminate\Database\Eloquent\Collection $subsidiaries */
            $subsidiaries = optional($process->company)->subsidiaries
        @endphp

        <template id="new-subsidiary">
            @component('dashboard.shared.box.steps-form.partials.subsidiary', [
                'key' => false,
                'subsidiary' => false,
                'step' => $step,
                'process' => $process
            ])
            @endcomponent
        </template>

        <div
            class="panel-group"
            id="subsidiaries"
            role="tablist"
            aria-multiselectable="true"
            data-last-id="{{ $subsidiaries ? $subsidiaries->count() : 0 }}">
            @foreach ($subsidiaries ?? [] as $key => $subsidiary)
                @php
                    $key++
                @endphp

                @component('dashboard.shared.box.steps-form.partials.subsidiary', compact('key', 'process', 'subsidiary', 'step'))
                @endcomponent
            @endforeach
        </div>

        <div class="box-add-new-subsidiary">
            <button type="button" class="btn btn-lg btn-primary" data-button-add-new-subsidiary>
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </section>

@endif
