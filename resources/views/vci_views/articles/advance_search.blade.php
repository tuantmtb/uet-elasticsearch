@extends('vci_views.layouts.master')

@section('content')
	<div id="center-content" style="min-height: 450px">
		<div class="head-content">
			<h3 class = "text-center">Lựa Chọn Tìm Kiếm Nâng Cao</h3>
		</div>
		<form action="{{route('search.advance.post')}}" method="GET" accept-charset="utf-8">
			{{csrf_field()}}
			<div class="search-components">
				<div class="portlet light">
					<div class="panel panel-default">
					  <div class="panel-heading"><h6>Bài Báo</h6></div>
					  <div class="panel-body">
						<input class="form-control" type="text" name="article_title" placeholder="Tiêu đề" style="width: 49%; display:inline-block" />
						<input class="form-control" type="text" name="article_abstract" placeholder="Abstract"  style="width: 49%; display:inline-block">
					  </div>
					</div>

					<div class="panel panel-default" style="width: 49%; display:inline-block">
						<div class="panel-heading">
							<h6>Tác Giả</h6>
						</div>
						<div class="panel-body">
							@if(count($authors) > 0)
								<select class="form-control drop_ids" name="author_id" id="author_ids" style="width: 60%;">
									<option value="">Tác giả</option>
									@foreach($authors as $author)
										<option value="{{$author->id}}">{{$author->name}}</option>
									@endforeach
								</select>
							@else
								<input type="text" name="author_name" placeholder="Tên tác giả" class="form-control" /> 
							@endif
						</div>
					</div>
					<div class="panel panel-default" style="width:49%; display:inline-block">
						<div class="panel-heading">
							<h6>Tạp Chí</h6>
						</div>
						<div class="panel-body">
							<select class="form-control drop_ids" name="journal_id" id="journal_ids" style="width:60%">
								<option value="">Chọn tạp chí</option>
								@foreach($journals as $journal)
									<option value="{{$journal->id}}">{{$journal->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="panel panel-default" style="width: 49%; display:inline-block">
						<div class="panel-heading">
							<h6>Năm</h6>
						</div>
						<div class="panel-body">
							<select class="form-control drop_ids" name="year" id="year" style="width:60%">
								<option value="">Chọn năm</option>
								@foreach($years as $year)
									<option value="{{$year}}">{{$year}}</option>
								@endforeach
							</select>
						</div>
					</div>
					{{-- <div class="panel panel-default">
						<div class="panel-heading">
							<h6>Ngôn ngữ</h6>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<input type="radio" name="language" value="vi"> Tiếng Việt | 
								<input type="radio" name="language" value="en"> Tiếng Anh
							</div>
						</div>
					</div> --}}
					<div class="send">
						<button class="default" type="submit">Gửi Tìm Kiếm</button>
					</div>
				</div>
			</div>	
		</form>
	</div>
@endsection	