@extends('layouts.manage_datatable')

@section('page-title')
    Backup database
@endsection

@section('portlet-title')
    <div class="portlet-title">
        <div class="actions">
            <button class="btn green" id="backup-btn">
                <i class="fa fa-database"></i> Sao lưu
            </button>
        </div>
    </div>
@endsection

@section('page-level-scripts')
    @parent
    <script>
        function confirmDelete(file_name) {
            bootbox.confirm("Có chắc chắn muốn xoá bản sao lưu này không?", function(result) {
                if (result) {
                    var dialog = bootbox.dialog({
                        message: '<p><i class="fa fa-spin fa-spinner"></i> Đang xử lý...</p>'
                    });
                    $.ajax({
                        method: 'post',
                        url: '{{route('manage.backup.delete')}}',
                        data: {
                            _token: window.Laravel.csrfToken,
                            file_name: file_name
                        },
                        success: function() {
                            dialog.find('.bootbox-body').html('<p><i class="fa fa-spin fa-spinner"></i> Xoá bản sao lưu thành công! Đang tải lại bảng...</p>');
                            window.LaravelDataTables["dataTableBuilder"].ajax.reload(function() {
                                dialog.modal('hide');
                            });
                        },
                        error: function() {
                            dialog.modal('hide');
                            toastr['error']('Đã có lỗi xảy ra. Vui lòng thử lại sau', 'Lỗi không xác định');
                        }
                    })
                }
            })
        }

        $(function() {
            $('#backup-btn').click(function(e) {
                var dialog = bootbox.dialog({
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Đang sao lưu...</p>'
                });
                $.ajax({
                    method: 'post',
                    url: '{{route('manage.backup.run')}}',
                    data: {_token: window.Laravel.csrfToken},
                    success: function() {
                        dialog.find('.bootbox-body').html('<p><i class="fa fa-spin fa-spinner"></i> Sao lưu thành công! Đang tải lại bảng...</p>');
                        window.LaravelDataTables["dataTableBuilder"].ajax.reload(function() {
                            dialog.modal('hide');
                        });
                    },
                    error: function() {
                        dialog.find('.bootbox-body').html('<p><i class="fa fa-spin fa-spinner"></i> Đã có lỗi xảy ra. Đang thử tải lại bảng...</p>');
                        window.LaravelDataTables["dataTableBuilder"].ajax.reload(function() {
                            dialog.modal('hide');
                        });
                    }
                })
            })
        });
    </script>
@endsection