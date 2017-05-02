<a href="{!! route('article.edit', $article->id) !!}" class="btn btn-sm btn-warning"
   style="margin-top: 5px">
    <i class="fa fa-edit"></i> Sửa
</a>
@if(is_null($article->is_reviewed))
    <a href="{!! route('manage.article.review', $article->id) !!}" class="btn btn-sm btn-success"
       style="margin-top: 5px">
        <i class="fa fa-check"></i> Duyệt
    </a>
    <a href="{!! route('manage.article.no_review', $article->id) !!}" class="btn btn-sm btn-danger"
       style="margin-top: 5px">
        <i class="fa fa-close"></i> Loại
    </a>
@endif