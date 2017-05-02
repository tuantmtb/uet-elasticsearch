@extends('vci_views.layouts.master')

@section('page-level-plugins.styles')
    @parent
    {!! Html::style('metronic/global/plugins/jstree/dist/themes/default/style.min.css') !!}
@endsection

@section('menu.home', 'active')

@section('head-more')
    <style>
        .calendar {
            margin: 10px;
        }

        .call-to-action-content {
            text-align: left !important;
        }

        .title-index {
            font-size: 2.2em !important;;
            font-weight: 300 !important;;
            line-height: 42px !important;;
            margin: 13px 0 2px 0 !important;
        }

        .description-index {
            font-size: 1.4em;
            font-weight: 300;
            letter-spacing: normal;
            line-height: 27px;
            margin: 0 0 14px 0;
        }

        /*--------CALENDAR-------*/
        article {
            display: block;
        }

        article.post .post-date {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
            float: left;
            margin-right: 10px;
            text-align: center;
        }

        article.post .post-date .day {
            color: #007f49;
            background: #F4F4F4;
            border-radius: 2px 2px 0 0;
            display: block;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
        }

        article.post .post-date .month {
            border-radius: 0 0 2px 2px;
            box-shadow: 0 -1px 0 0 rgba(0, 0, 0, 0.07) inset;
            color: #FFF;
            font-size: 0.9em;
            padding: 0 10px 2px;
            background-color: #007f49;
        }

        .description-vci {
            color: #777;
            line-height: 24px;
            margin: 0 0 20px;
        }
    </style>
@endsection

@section('content')
    <div id="" style="min-height: 450px; ">
        <div class="row">
            <div class="col-md-12">
                @include('main-content')
                {{--<div class="portlet light">--}}
                {{--<div class="row mb-xlg">--}}
                {{--<div class="col-sm-7">--}}
                {{--<h2 class="heading-primary">Giới thiệu</h2>--}}

                {{--<p class="mt-xlg description-vci">--}}
                {{--Ngày nay, các nhà khoa học Việt Nam đang miệt mài nghiên cứu để đóng góp công sức cho sự--}}
                {{--phát triển của đất nước và thế giới. Các công trình nghiên cứu thường được công bố trên--}}
                {{--các chuyên san, hội nghị quốc tế và trong nước. Với các bài báo được công bố trong các--}}
                {{--tạp chí quốc tế, việc tìm kiếm thông tin được thực hiện một cách dễ dàng với các cơ sở--}}
                {{--dữ liệu về các chuyên san và công bố như Scopus hoặc Web of Science. Tuy nhiên, với các--}}
                {{--chuyên san trong nước, một cơ sở dữ liệu như thế chưa được xây dựng. Điều này sẽ gây ra--}}
                {{--khó khăn cho các nhà khoa học trong việc tìm kiếm chuyên san phù hợp, tìm kiếm tài liệu--}}
                {{--tham khảo; các cơ quan quản lý cũng sẽ gặp khó khăn trong việc thống kê. Đầu năm 2016,--}}
                {{--Đại học Quốc gia Hà Nội đã triển khai xây dựng hệ thống cơ sở dữ liệu Chỉ số trích dẫn--}}
                {{--Việt Nam (Vietnamese Citation Index – VCI) với các chức năng chính: tìm kiếm thông tin--}}
                {{--bài báo (theo tiêu đề, tác giả, cơ quan,…) được công bố trên các chuyên san trong nước;--}}
                {{--thống kê số lượng các trích dẫn cho một tác giả, bài báo, chuyên san.--}}


                {{--</p>--}}
                {{--</div>--}}
                {{--<div class="col-sm-5">--}}
                {{--<h2 class="heading-primary">Tin tức và sự kiện</h2>--}}

                {{--<div class="row calendar">--}}
                {{--<div class="col-md-2">--}}
                {{--<article class="post">--}}
                {{--<div class="post-date">--}}
                {{--<span class="day">01</span>--}}
                {{--<span class="month">Oct</span>--}}
                {{--</div>--}}
                {{--</article>--}}
                {{--</div>--}}
                {{--<div class="col-md-10">--}}
                {{--<a href="http://www.hoithaoquocgiacntt.ac.vn/index.html">Hội thảo Quốc gia lần thứ--}}
                {{--XIX "Một số vấn đề chọn lọc của Công nghệ thông--}}
                {{--tin và Truyền thông</a>--}}

                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="row calendar">--}}
                {{--<div class="col-md-2">--}}
                {{--<article class="post">--}}
                {{--<div class="post-date">--}}
                {{--<span class="day">14</span>--}}
                {{--<span class="month">Aug</span>--}}
                {{--</div>--}}
                {{--</article>--}}
                {{--</div>--}}
                {{--<div class="col-md-10">--}}
                {{--Hạn nộp bài cho <a href="http://www.acm-soict.org/">--}}
                {{--The Seventh International Symposium on Information and Communication Technology--}}
                {{--(SoICT 2016)--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="row calendar">--}}
                {{--<div class="col-md-2">--}}
                {{--<article class="post">--}}
                {{--<div class="post-date">--}}
                {{--<span class="day">31</span>--}}
                {{--<span class="month">May</span>--}}
                {{--</div>--}}
                {{--</article>--}}
                {{--</div>--}}
                {{--<div class="col-md-10">--}}

                {{--Hạn nộp bài cho <a href="http://www.icabse.org/">The National Foundation for Science--}}
                {{--and Technology--}}
                {{--Development Conference--}}
                {{--on Information and Computer Science (NICS) 2016</a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
@endsection

@section('page-level-plugins')
    @parent
@endsection

@section('scripts-more')
    @parent
@endsection