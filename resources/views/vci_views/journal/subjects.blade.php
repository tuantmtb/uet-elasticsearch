@extends('vci_views.layouts.master')

@section('page-level-plugins.styles')
    @parent
    {!! Html::style('metronic/global/plugins/jstree/dist/themes/default/style.min.css') !!}
@endsection

@section('content')
    <div id="center-content" style="min-height: 450px">
        <div class="row clearfix columns-widget">
            <div class="col-left col-xs-12 col-md-12 col-sm-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            Tạp chí {{$journal->name}}
                        </div>
                        <div class="actions">
                            <button class="btn btn-primary" onclick="update()">Cập nhật</button>
                        </div>
                    </div>
                    <div class="portlet-body" style="overflow-x: hidden">
                        <div id="tree" class="jstree jstree-default">Đang tải...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-level-plugins')
    @parent
    {!! Html::script('metronic/global/plugins/jstree/dist/jstree.min.js') !!}
@endsection

@section('scripts-more')
    @parent
    <script>
        const $api_roots = '{!! route('api.subjects.roots') !!}';
        const $api_children = (id) => {
            return '{!! route('api.subjects.children', ['id' => '_ID']) !!}'.replace('_ID', id);
        };
        const $api_show = (id) => {
            return '{!! route('api.subjects.show', ['id' => '_ID']) !!}'.replace('_ID', id);
        };

        function update() {
            const subject_ids = $('#tree').jstree(true).get_checked();
            UI('center-content').block();
            $.ajax({
                method: 'post',
                url: '{!! route('api.journal.updateSubjects', [$journal->id]) !!}',
                data: {
                    _token: Laravel.csrfToken,
                    subject_ids: subject_ids,
                },
                success: () => {
                    toastr['success']('Đã cập nhật chủ đề thành công', 'Cập nhật chủ đề');
                    UI('center-content').unblock();
                },
                error: () => {
                    toastr['error']('Lỗi không xác định', 'Lỗi cập nhật');
                    UI('center-content').unblock();
                },
            });
        }

        $(document).ready(() => {
            const tree = $('#tree');

            tree.on('ready.jstree', (e, data) => {
                const tree = data.instance;
                let opened = JSON.parse('{!! json_encode($opened) !!}');
                const subject_ids = JSON.parse('{!! json_encode($subject_ids) !!}');

                const dolast = () => {
                    $(subject_ids).each((i, id) => {
                        tree.select_node(id, true);
                    });
                    applyFilter(subject_ids);
                };
                const callback = () => {
                    if (opened.length > 0) {
                        const first = opened[0];
                        opened = opened.splice(1);
                        tree.open_node(first, callback);
                    } else {
                        dolast();
                    }
                };
                callback();
            }).jstree({
                plugins: ['checkbox'],
                core: {
                    strings: {
                        'Loading ...': 'Đang tải ...'
                    },
                    check_callback: true,
                    expand_selected_onload: true,
                    multiple: true,
                    force_text: true,
                    dblclick_toggle: true,
                    themes: {
                        variant: "large",
                        dots: true,
                        icons: false,
                        responsive: true,
                    },
                    data: {
                        url: (node) => {
                            return node.id === '#' ? $api_roots : $api_children(node.id);
                        },
                        data: (node) => {
                            return $api_show(node.id);
                        }
                    },
                },
                checkbox: {
                    three_state: true,
                }
            });
        });
    </script>
@endsection