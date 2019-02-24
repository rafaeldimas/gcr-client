@component('dashboard.shared.box.grid')
    @slot('boxTitle')
        {{ $boxTitle }}
    @endslot
    @slot('gridHead')
        {{ $gridHead }}
    @endslot
    @slot('gridBody')
        @foreach($models as $model)
            <tr>
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
        {{ $models->links() }}
    @endslot
@endcomponent
