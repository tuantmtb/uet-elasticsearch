<a href="{!! route('article.edit', $article->id) !!}" class="btn btn-sm btn-warning"
   style="margin-top: 5px">
    <i class="fa fa-edit"></i> Sửa
</a>
{{Form::open(['method' => 'post', 'route' => ['article.review', $article->id], 'style' => 'display: inline'])}}
@if(!$article->isReviewed())
    <button class="btn btn-sm btn-success" name="is_reviewed" value="1"
       style="margin-top: 5px">
        <i class="fa fa-check"></i> Duyệt
    </button>
@endif
@if(!$article->isNoReviewed())
    <button class="btn btn-sm btn-danger" name="is_reviewed" value="0"
       style="margin-top: 5px">
        <i class="fa fa-close"></i> Loại
    </button>
@endif
{{Form::close()}}