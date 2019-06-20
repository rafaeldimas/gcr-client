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
                <tr style="background-color: {{$model->statusLatestFirst->color}};">
            @else
                <tr>
            @endif
                @foreach($fields as $relation => $field)
                    @if(is_array($field))
                        @foreach($field as $childField)
                            <td>{{ $model->{$relation}->{$childField} }}</td>
                        @endforeach
                    @else
                        <td style="
                            @if($field === 'color')
                            background-color: {{ $model->{$field} }};
                            @endif
                        ">
                            @if($field === 'protocol')
                                <a href="{{ $model->linkShow() }}" class="@if($model->statusLatestFirst->text_white) text-white @endif">
                            @endif
                                {{ $model->{$field} }}
                            @if($field === 'protocol')
                                </a>
                            @endif
                        </td>
                    @endif
                @endforeach
                <td>
                    <a href="{{ $model->linkEdit() }}" class="btn text-bold
                        @if ($model instanceof Gcr\Process)
                            @if(!$model->editing) disabled @endif
                            @if($model->statusLatestFirst->text_white) text-white @endif
                        @endif
                        ">
                        Editar
                    </a>

                    @if(!($model instanceof Gcr\Process))
                        <form action="{{ $model->linkDestroy() }}" method="post" style="display: inline;">
                            @method('DELETE ')
                            @csrf
                            <button type="submit" class="btn btn-link text-bold">Excluir</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    @endslot
    @slot('boxFooter')
        {{ $models->appends(['type_company' => request('type_company')])->links() }}
    @endslot
@endcomponent
