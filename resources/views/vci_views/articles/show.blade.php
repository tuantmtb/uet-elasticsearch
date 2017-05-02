@extends('vci_views.layouts.master')

@section('content')
    <div id="center-content" style="min-height: 450px">
        <div class="row clearfix columns-widget columns3-9">
            <div class="content" style="background-color: #fff">
                <div class="content-above">
                    <h3>{{$article->title}}</h3>
                    <hr/>
                    <div class="row">
                        <div class="col-md-3">
                            <p>
                                <a href="{{$article->uri}}" class="btn dark btn-outline"><i class="fa fa-download"></i>
                                    Tải về
                                </a>
                            </p>
                        </div>
                        <div class="col-md-9 pull-right">
                            <div class="pull-right">
                                @if(Auth::check() && Auth::user()->can('manage'))
                                    <a href="{{route('user.article.edit',$article->id)}}" class="btn btn-warning">
                                        <i class="fa fa-edit"></i> Sửa
                                    </a>

                                    @if($article->is_reviewed != null && $article->is_reviewed == 1)
                                        <span>
                                                <span class="alert alert-success">
                                                Đã duyệt bài </span>
                                            </span>
                                    @endif

                                    @if($article->is_reviewed != null && $article->is_reviewed == 0)
                                        <span>
                                            <span class="alert alert-danger">
                                            Đã bị loại </span>
                                        </span>
                                    @endif

                                    @if(!isset($article->is_reviewed) || $article->is_reviewed == null)

                                        <a href="{{route('manage.article.review',$article->id)}}"
                                           class="btn btn-success">
                                            <i class="fa fa-check"></i> Duyệt
                                        </a>
                                        <a href="{{route('manage.article.no_review',$article->id)}}"
                                           class="btn btn-danger">
                                            <i class="fa fa-close"></i> Loại
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="author">
                        @if($article->authors->count() > 0)
                            <div>
                                <span class="label-txt">Tác giả: </span>
                                @foreach($article->authors as $index=>$author)
                                    <a style="color:#B12A23;"
                                       href="{{url('/') .'/search?text_search='. $author->name . '&cate_search=2'}}">
                                        {{$author->name}}
                                    </a>
                                    @if($index+1 < $article->authors->count())
                                        ,
                                    @endif
                                @endforeach
                            </div>

                            <div class="row">
                                <div class="col-md-1">
                                    <div class="label-txt">Cơ quan:</div>
                                </div>
                                <div class="col-md-11">
                                    @foreach($organizes as $organize)
                                        <div>
                                            <a href="{{route('organize.show',$organize->id)}}">{{$organize->name}}</a>
                                        </div>

                                    @endforeach
                                </div>
                            </div>
                        @endif


                    </div>
                    <p>
                        <section>

                            Tạp chí:
                            @if($article->journal !=null)
                                <a href="{{route('journal.articles', [$article->journal->id])}}">{{$article->journal->name}}</a>
                                <br/>
                            @endif
                            Volume {{$article->volume}} Số {{$article->number}} Năm <a
                                    href="{{route('year.articles', [$article->year])}}">{{$article->year}}</a>
                        </section>
                    </p>
                </div>
                <div class="content-bottom">
                    <div>
                        <div>
                            <h3>Tóm tắt</h3>
                            <div>
                                {{$article->abstract}}
                            </div>
                            <hr/>
                        </div>

                        <div>
                            <h3>Từ khóa</h3>
                            <div>
                                {{$article->keyword}}
                            </div>
                            <hr/>
                        </div>
                        <div>
                            <h3>Ngôn ngữ</h3>
                            <div>
                                @if($article->language == 'vi')
                                    {{'Tiếng Việt'}}
                                @elseif($article->language == 'en')
                                    {{'Tiếng Anh'}}
                                @elseif($article->language == 'cn')
                                    {{'Tiếng Trung'}}
                                @elseif($article->language == 'fr')
                                    {{'Tiếng Pháp'}}
                                @elseif($article->language == 'ru')
                                    {{'Tiếng Nga'}}
                                @elseif($article->language == 'ge')
                                    {{'Tiếng Đức'}}
                                @elseif($article->language == 'jo')
                                    {{'Tiếng Nhật'}}
                                @endif

                            </div>
                            <hr/>
                        </div>
                        <div>
                            <h3>Tham khảo</h3>
                            <div>
                                <?php $tmp = 1;?>
                                @foreach($cited_articles as $item)
                                    <div>
                                        <a href="{{route('show.article', [$item->id])}}">{{$tmp++}}
                                            .{{$item->title}}</a>
                                    </div>
                                @endforeach
                                <div>

                                    {!! nl2br(e($article->reference)) !!}

                                </div>
                            </div>
                            <hr/>
                        </div>
                        <div>
                            <h3>Các bài trích dẫn đến</h3>
                            <div>
                                <?php $tmp = 0;?>

                                @foreach($citations as $citation)
                                    <?php $tmp++;?>
                                    <div>
                                        <span>{{$tmp .'. '}}</span>
                                        {{$citation["authors"] != '' ? \App\Facade\VciHelper::mapAuthorsToNames($citation["authors"]) . '. ': ''}}
                                        <a href="{{$citation["uri"]}}">
                                            {{$citation["title"]}}
                                        </a> {{$citation["journalName"] != '' ? 'in '. $citation["journalName"]: ''}}
                                        (
                                        {{$citation["volume"]!=''? 'Vol. '.$citation["volume"]:''}}
                                        {{$citation["number"]!=''? ', No. '.$citation["number"]:''}}
                                        {{$citation["year"]!=''? ' '.$citation["year"]:''}}
                                        )


                                    </div>
                                @endforeach
                            </div>
                            <hr/>
                        </div>
                        @if(Auth::check() && Auth::user()->can('manage'))
                            <div>
                                <h3>Thông tin khác</h3>
                                <div>
                                    Lấy từ: <a href="{{$article->uri}}">{{$article->source}}</a>
                                </div>
                                <div style="margin-top: 5px">
                                    <div>
                                        Ngày tạo: {{$article->created_at}}
                                    </div>
                                    <div>
                                        Ngày cập nhật: {{$article->updated_at}}
                                    </div>
                                    <div>
                                        @if(isset($article->editor))
                                            Người sửa: {{$article->editor->name}}
                                        @else
                                            Dữ liệu được lấy tự động và chưa được duyệt
                                        @endif
                                    </div>

                                </div>

                                <hr/>
                            </div>
                        @endif
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection