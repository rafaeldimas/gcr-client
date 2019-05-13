@component('dashboard.shared.box.grid')
    @slot('boxTitle')
        {{ $boxTitle }}
    @endslot
    @slot('gridHead')
        {{ $gridHead }}
    @endslot
    @slot('gridBody')
        @foreach($models as $model)
            @if ($model instanceof Gcr\Process)
                <tr style="background-color: {{$model->status->color}};">
            @else
                <tr>
            @endif
                @foreach($fields as $relation => $field)
                    @if(is_array($field))
                        @foreach($field as $childField)
                            <td>{{ $model->{$relation}->{$childField} }}</td>
                        @endforeach
                    @else
                        <td>{{ $model->{$field} }}</td>
                    @endif
                @endforeach
                <td>
                    <a href="{{ route($linkEdit, [$model]) }}">
                        Editar
                    </a>
                </td>
            </tr>
        @endforeach
    @endslot
    @slot('boxFooter')
        {{ $models->appends(['type_company' => request('type_company')])->links() }}
    @endslot
@endcomponent
