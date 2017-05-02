@extends('vci_views.layouts.master')

@section('content')

<div class="row">
	<div class="col-md-3">
		<div class="portlet light">
			<div class="portlet-title">
				<h4 class="text-center">Thông tin người dùng</h4>
			</div>
			<div class="port-body">
				{{-- <div class="panel"> --}}
					{{-- <div class="panel-body"> --}}
						<ul>
							<li>Họ tên: <strong>{{$user->name}}</strong></li>
							<li>Email: <strong>{{$user->email}}</strong></li>
							<li>Số lượng bài báo: <strong>{{count($user->articles)}}</strong></li>
							@if(Auth::check())
								@if(Auth::user()->id == $user->id)
									<li style="margin-top: 10px">
										<a href="{{route('user.article.create')}}" class="btn grey-mint btn-outline sbold" style="margin-bottom: 10px"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm bài báo</a> 
									</li>
								@endif
							@endif
						</ul>
					{{-- </div> --}}
				{{-- </div> --}}
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="portlet light">
			<div class="portlet-title">
				<h4 class="text-center">
					Danh sách bài báo
				</h4>
			</div>
			<div class="portlet-body">
				@include('vci_views.partials.right_content')
			</div>
		</div>	
	</div>
</div>
@endsection