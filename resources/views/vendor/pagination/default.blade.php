@if ($paginator->hasPages())
    <ul class="pagination">
        @if ($paginator->onFirstPage())
            <li class="disabled"><span class="tooltips" data-original-title="Trang đầu">
                    <i class="fa fa-angle-double-left"></i>
                </span></li>
            <li class="disabled"><span class="tooltips" data-original-title="Trang trước">
                    <i class="fa fa-angle-left"></i>
                </span></li>
        @else
            <li><a href="{{$paginator->url(1)}}" rel="prev" class="tooltips"
                   data-original-title="Trang đầu">
                    <i class="fa fa-angle-double-left"></i>
                </a></li>
            <li><a href="{{$paginator->previousPageUrl()}}" rel="prev" class="tooltips"
                   data-original-title="Trang trước">
                    <i class="fa fa-angle-left"></i>
                </a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{$url}}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li><a href="{{$paginator->nextPageUrl()}}" rel="next" class="tooltips"
                   data-original-title="Trang tiếp">
                    <i class="fa fa-angle-right"></i>
                </a></li>
            <li><a href="{{$paginator->url($paginator->lastPage())}}" rel="next" class="tooltips"
                   data-original-title="Trang cuối">
                    <i class="fa fa-angle-double-right"></i>
                </a></li>
        @else
            <li class="disabled"><span class="tooltips" data-original-title="Trang tiếp">
                    <i class="fa fa-angle-right"></i>
                </span></li>
            <li class="disabled"><span class="tooltips" data-original-title="Trang cuối">
                    <i class="fa fa-angle-double-right"></i>
                </span></li>
        @endif
    </ul>
@endif
