@can('admin')
<h3>{{ $step['label'] }}</h3>
<section>
    <div class="row">
        <div class="form-group col-xs-12 col-md-4">
            <label for="status">Status</label>
            <select id="status" name="process[status]" class="form-control">
                @foreach(Gcr\Status::all() as $status)
                    <option value="{{ $status->id }}" @if($status->isLastStatusByProcess($process)) selected @endif>{{ $status->label }}</option>
                @endforeach
            </select>
        </div>
    </div>
</section>
@endcan
