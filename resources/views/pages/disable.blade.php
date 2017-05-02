@extends('layouts.page')

@section('page-level-styles')
    @parent
    <style>
        .caption-desc {
            line-height: normal;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
@endsection

@section('page-body')
    <div class="row">
        <div class="col-lg-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="caption-subject bold" style="color: #31849b; font-size: 18pt;">
                            V-CitationGate
                        </div>
                        <div class="caption-desc bold" style="font-size: 11pt; color: #c0504d">
                            Vietnam Citation Gateway
                            <br>Tư liệu nghiên cứu Việt Nam
                        </div>
                    </div>
                </div>
                <div class="portlet-body text-justify">
                    <div class="alert alert-info">
                        <p><strong>Thông báo: </strong>Chức năng này hiện tạm khóa. Cần đăng nhập tài khoản</p>

                        <p class="margin-top-10">
                            <strong>Liên hệ: </strong><span>vcgate@vnu.edu.vn</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection