	
    <table id="{{ $datatable_id }}" class="table table-striped table-hover" cellspacing="0">
        <thead>
            <tr>
            	@if ($titles)
                    @for ($i = 0; $i < count($titles); $i++)
                        <th>{!! $titles[$i] !!}</th>
                    @endfor
                @else
                    @for ($i = 0; $i < count($array_values); $i++)
                        <th>{!! $array_values[$i] !!}</th>
                    @endfor
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($values as $value)
            <tr>
                @for ($i = 0; $i < count($array_values); $i++)
                    @if ($i == 0)
                        <td>
                            @if ($route)
                            <a href="{{ route($link . '.' . $route, $value) }}" class="text-success">
                                <strong>{!! $value->getAttribute($array_values[$i]) !!}</strong>
                            </a>
                            @else
                            <span class="text-info">
                                <strong>{!! $value->getAttribute($array_values[$i]) !!}</strong>
                            </span>
                            @endif
                        </td>
                    @else
                        @if (filter_var($value->getAttribute($array_values[$i]), FILTER_VALIDATE_EMAIL))
                            <td>
                                <a href="mailto:{!! $value->getAttribute($array_values[$i]) !!}" class="text-info">
                                    {!! $value->getAttribute($array_values[$i]) !!}
                                </a>
                            </td>
                        @else
                            <td>{!! $value->getAttribute($array_values[$i]) !!}</td>
                        @endif
                    @endif
                @endfor
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
            	@if ($titles)
                    @for ($i = 0; $i < count($titles); $i++)
                        <th>{!! $titles[$i] !!}</th>
                    @endfor
                @else
                    @for ($i = 0; $i < count($array_values); $i++)
                        <th>{!! $array_values[$i] !!}</th>
                    @endfor
                @endif
            </tr>
        </tfoot>
    </table>