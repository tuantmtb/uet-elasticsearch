@if(isset($journal->subjects))
    <ul>
        @foreach($journal->subjects as $subject)
            <li>
                {{$subject->name}}
            </li>
        @endforeach
        <li>
            <a href="{!! route('manage.journal.subjects', [$journal->id]) !!}" class="btn btn-sm btn-default">
                <i class="fa fa-edit"></i> Quản lý
            </a>
        </li>
    </ul>
@endif