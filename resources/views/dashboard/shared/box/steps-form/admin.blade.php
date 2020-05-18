@can('admin')
<h3>{{ $step['label'] }}</h3>
<section>
    <div class="row">
        <div class="form-group col-xs-12 col-md-4">
            <label for="process[status_id]">Status</label>
            <select id="process[status_id]" name="process[status_id]" class="form-control">
                @foreach(Gcr\Status::all() as $status)
                    <option value="{{ $status->id }}" @if($status->isLastStatusByProcess($process)) selected @endif>{{ $status->label }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-xs-12 col-md-4">
            <label for="process[status_description]">Descrição do Status</label>
            <textarea id="process[status_description]" name="process[status_description]" class="form-control"></textarea>
        </div>
    </div>
</section>
@endcan
