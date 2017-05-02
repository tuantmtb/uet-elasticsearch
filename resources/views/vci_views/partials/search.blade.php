{{--<form action="{{route('search.articles')}}" method="get" accept-charset="utf-8">--}}
{{--{{csrf_field()}}--}}
{{--<div class="row">--}}
{{--<div class="form-group col-md-5" style="width:40%; display: inline-block">--}}
{{--<input class="form-control" type="text" name="text_search" placeholder="Nội dung tìm kiếm ..." required/>--}}
{{--</div>--}}
{{--<div class="form-group col-md-5" style="width:40%; display: inline-block">--}}
{{--<select class="form-control" name="cate_search">--}}
{{--<option value="1">Tiêu đề bài báo</option>--}}
{{--<option value="2">Tên tác giả</option>--}}
{{--<option value="3">Tạp chí</option>--}}
{{--</select>--}}
{{--</div>--}}
{{-- <div class="form-group"> --}}
{{--<button type="submit" class="btn btn-primary col-md-2">Tìm kiếm</button>--}}
{{-- </div> --}}
{{--</div>--}}

{{--</form>--}}
{{--<p style="padding-right: 3rem;margin-bottom: 3rem; ">--}}
{{--<a href="{{route('search.advance.get')}}" title="Tìm kiếm" class="pull-right"--}}
{{--style="text-decoration: underline !important">--}}
{{--<span>Tìm kiếm nâng cao</span>--}}
{{--</a>--}}
{{--</p>--}}
<div class=" portlet light">
    <div class=" portlet-body">

        <div class="row">
            {!! Form::open(['method' => 'GET', 'role' => 'form', 'id' => 'search-form', 'route' => 'search.article']) !!}
            <div class="form-group col-md-7" style="display: inline-block">
                {{--{!! Form::text('title', '', ['class' => 'form-control', 'id' => 'search-main-field', 'placeholder'=>'Nội dung tìm kiếm ...']) !!}--}}
                {!! Form::text('text', '', ['class' => 'form-control', 'placeholder'=>'Nội dung tìm kiếm ...']) !!}
            </div>

            <div class="form-group col-md-3" style="display: inline-block">
                <select class="form-control" id="search-main-type" name="field">
                    @foreach(['title' => 'Tiêu đề', 'abstract' => 'Tóm tắt', 'author' => 'Tác giả', 'journal' => 'Tạp chí'] as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
            <div class=" col-md-2">
                <button type="submit" class="btn btn-primary" id="search-submit">
                    Tìm kiếm
                    <i class="fa fa-search"></i>
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>