function onError() {
    toastr['error']('Vui lòng tải lại trang', 'Đã có lỗi xảy ra');
}

$(document).ready(function () {
    var tree = $('#tree');
    var search = $('#search');
    var fullscreen = $('.fullscreen');

    tree.on('rename_node.jstree', function (e, data) {
        if (data.text !== data.old) {
            UI('portlet-body').block();
            $.ajax({
                method: 'POST',
                url: $pathWebsite + '/api/organizes/' + data.node.id + '/rename',
                data: {name: data.text, _token: Laravel.csrfToken},
                success: function () {
                    toastr['info']('Đã đổi tên ' + data.old + ' thành ' + data.text, 'Đổi tên cơ quan');
                    UI('portlet-body').unblock();
                },
                error: onError
            })
        }
    }).on('delete_node.jstree', function (e, data) {
        UI('portlet-body').block();
        $.ajax({
            method: 'POST',
            url: $pathWebsite + '/api/organizes/' + data.node.id + '/delete',
            data: {_token: Laravel.csrfToken},
            success: function () {
                toastr['info']('Đã xoá ' + data.node.text, 'Xoá cơ quan');
                UI('portlet-body').unblock();
            },
            error: onError,
        });
    }).on('move_node.jstree', function (e, data) {
        if (data.parent !== data.old_parent) {
            UI('portlet-body').block();
            var parent = data.instance.get_node(data.parent);
            $.ajax({
                method: 'POST',
                url: $pathWebsite + '/api/organizes/' + data.node.id + '/move',
                data: {_token: Laravel.csrfToken, parent_id: data.parent, position: data.position},
                success: function () {
                    if (parent.text !== undefined) {
                        toastr['info']('Đã chuyển ' + data.node.text + ' vào ' + parent.text, 'Chuyển cơ quan')
                    } else {
                        toastr['info']('Đã chuyển ' + data.node.text + ' ra ngoài', 'Chuyển cơ quan')
                    }
                    UI('portlet-body').unblock();
                },
                error: onError
            });
        }
    }).on('ready.jstree', function (e, data) {
        if (window.jstree_data) {
            var selected = window.jstree_data.selected;
            var opened = JSON.parse(window.jstree_data.opened).reverse();
            if (opened.length > 0) {
                var callback = function () {
                    opened = opened.splice(1);
                    if (opened.length > 0) {
                        data.instance.open_node(opened[0] + '', callback);
                    } else {
                        data.instance.select_node(selected);
                        Scroll(selected).toTop();
                    }
                };
                data.instance.open_node(opened[0] + '', callback);
            } else {
                data.instance.select_node(selected);
                Scroll(selected).toVisible();
            }
        }
    }).on('search.jstree', function (e, data) {
        if (data.nodes.length > 0) {
            var first = data.nodes[0];
            Scroll(first.id).toVisible();
        }
    }).jstree({
        plugins: ['contextmenu', 'dnd'],
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
                responsive: true
            },
            data: {
                url: function (node) {
                    return node.id === '#' ? $pathWebsite + '/api/organizes/roots' : $pathWebsite + '/api/organizes/' + node.id + '/children';
                },
                data: function (node) {
                    return $pathWebsite + '/api/organizes/' + node.id;
                }
            }
        },
        contextmenu: {
            show_at_node: false,
            items: function () {
                return {
                    copy_id: {
                        _disabled: function(data) {
                            var inst = $.jstree.reference(data.reference);
                            return inst.get_selected().length !== 1;
                        },
                        label: 'Sao chép mã cơ quan',
                        action: function (data) {
                            var inst = $.jstree.reference(data.reference);
                            var obj = inst.get_node(data.reference);
                            copyToClipboard(obj.id);
                            toastr['info']('Đã sao chép mã ' + obj.id, 'Sao chép mã cơ quan');
                        }
                    },
                    create: {
                        _disabled: false,
                        label: "Tạo",
                        action: function(data) {
                            var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                            window.location = $pathWebsite + '/manage/organize/create?parent_id=' + obj.id;
                        }
                    },
                    rename: {
                        _disabled: false,
                        label: "Đổi tên",
                        action: function(data) {
                            var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                            inst.edit(obj);
                        }
                    },
                    edit: {
                        _disabled: function (data) {
                            var inst = $.jstree.reference(data.reference);
                            return inst.get_selected().length !== 1;
                        },
                        label: 'Sửa',
                        action: function (data) {
                            var inst = $.jstree.reference(data.reference);
                            var obj = inst.get_node(data.reference);
                            window.location = $pathWebsite + '/manage/organize/' + obj.id + '/edit';
                        }
                    },
                    remove: {
                        _disabled: false,
                        label: "Xoá",
                        action: function (data) {
                            var inst = $.jstree.reference(data.reference),
                                obj = inst.get_node(data.reference);
                            if (inst.is_selected(obj)) {
                                inst.delete_node(inst.get_selected());
                            } else {
                                inst.delete_node(obj);
                            }
                        }
                    },
                    group: {
                        _disabled: function (data) {
                            var inst = $.jstree.reference(data.reference);
                            var ids = inst.get_selected();
                            return ids.length < 2;
                        },
                        label: 'Gộp',
                        action: function(data) {
                            UI('portlet-body').block();
                            var inst = $.jstree.reference(data.reference);
                            var ids = inst.get_selected();
                            var saved = inst.get_selected(true)[0];
                            $.ajax({
                                method: 'POST',
                                url: $pathWebsite + '/api/organizes/merge',
                                data: {_token: Laravel.csrfToken, ids: ids},
                                success: function (response) {
                                    toastr['info']('Đã gộp các cơ quan vào ' + saved.text, 'Gộp cơ quan');
                                    inst.refresh();
                                    UI('portlet-body').unblock();
                                    response.opened.reverse().forEach(function (id) {
                                        inst.open_node(id);
                                    });
                                    inst.select_node(saved);
                                },
                                error: onError
                            })
                        }
                    }
                };
            }
        }
    });

    fullscreen.click(function () {
        // const slimScrollDiv = $('.slimScrollDiv');
        // const scroller = $('.scroller');
        const portlet_body = $('.portlet-body');

        if (fullscreen.hasClass('on')) {
            // slimScrollDiv.css('height', '500px');
            // scroller.css('height', '500px');
            portlet_body.removeClass('fullscreen');
        } else {
            // slimScrollDiv.css('height', 'auto');
            // scroller.css('height', 'auto');
            portlet_body.addClass('fullscreen');
        }
    });

    search.keyup(function (e) {
        const code = e.which | e.code;
        if (code === 13) {
            doSearch(search.val());
        }
    });
});

function doSearch(q) {
    UI('portlet-body').block();
    if (q.trim() === '') {
        const inst = $.jstree.reference(tree);
        inst.show_all();
        UI('portlet-body').unblock();
    } else {
        $.ajax({
            url: $pathWebsite + "/api/organizes/search/jstree?q=" + q.trim(),
            method: 'GET',
            success: function (response) {
                var inst = $.jstree.reference(tree);
                var opened = response.opened;
                var result = response.result;
                inst.show_all();
                var callback = function() {
                    response.hidden.forEach(function(id) {
                        $('#' + id).addClass('jstree-hidden');
                    });
                    response.result.forEach(function(id) {
                        $('#' + id + '_anchor').addClass('jstree-search');
                    });
                    UI('portlet-body').unblock();
                };
                if (opened.length > 0) {
                    const recursive = function() {
                        opened = opened.splice(1);
                        if (opened.length > 0) {
                            inst.open_node(opened[0] + '', recursive);
                        } else {
                            callback();
                        }
                    };
                    inst.open_node(opened[0] + '', recursive);
                } else {
                    callback();
                }
            },
            error: onError
        })
    }
}