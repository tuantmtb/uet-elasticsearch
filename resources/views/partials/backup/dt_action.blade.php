<a href="{{route('manage.backup.download', $file->name)}}" class="btn btn-sm green">
    <i class="fa fa-download"></i> Tải xuống
</a>
<a href="javascript:" class="btn btn-sm red" onclick="confirmDelete('{{$file->name}}')">
    <i class="fa fa-trash"></i> Xoá
</a>