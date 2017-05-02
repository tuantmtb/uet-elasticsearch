@if($article->journal_id)
    <a href="{!! route('journal.articles', $article->journal_id) !!}">{{$article->journal->name}}</a>
@endif