<tr>
    <th style="text-align: right">{{$stt}}.</th>
    <td colspan="3"></td>
</tr>
@foreach($map as $key => $value)
    <tr>
        <td style="text-align: right">{{$value}}</td>
        <td>
            <a href="javascript:" id="cite-{{$stt-1}}-{{$key}}" class="editable" data-original-title="{{$value}}"
               @if(in_array($key, ['volume', 'number', 'year', 'cites_count'])) data-type="number" @elseif($key === 'source') data-type="url" @endif
            >
            </a>
        </td>
        <td></td>
        <td></td>
    </tr>
@endforeach