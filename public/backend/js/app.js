var ajax_init = function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
};

var select2_init = function() {
    var select = $('.select2');
    if (select.length > 0) {
        select.select2();
        if (select.data('default') !== 'undefined') {
            var def = select.data('default');
            select.val(def).trigger('change');
        }

    }
};
var tr_pointer_click = function () {
    $('.navigation-tr').on('click', function () {
        $.ajax({
            cache: false,
            type: 'GET',
            url: $(this).data('url'),
            async: false,
            data: {'id': $(this).data('id')},
            success: function (data) {
                if (data.result) {
                    $('#name').val(data.data.name);
                    $('#weight').val(data.data.weight);
                    $('#id').val(data.data.id);
                    $('#m_id').val(data.data.m_id);
                    $('.nav-edit-form-submit').attr('disabled', false);
                } else {
                    alert('获取失败，请联系管理员');
                    $('.nav-edit-form-submit').attr('disabled', true);
                }
            },
            error: function (request) {
                var message = '请稍后再试！';
                if (request.status === 404) {
                    message = '请求地址不存在，请联系管理员确认！'
                } else if(request.status >= 500) {
                    message = '请求异常，请稍后再试！'
                }
                $('.nav-edit-form-submit').attr('disabled', true);
                alert(message);
            }
        });
    });

    $('.sections-tr').on('click', function () {
        $.ajax({
            cache: false,
            type: 'GET',
            url: $(this).data('url'),
            async: false,
            data: {'id': $(this).data('id')},
            success: function (data) {
                if (data.result) {
                    $('#name').val(data.data.name);
                    $('#weight').val(data.data.weight);
                    $('#id').val(data.data.id);
                    $('#m_id').val(data.data.m_id);
                    $('input[name="position"]').get(data.data.position).checked = true;
                    $('.section-edit-form-submit').attr('disabled', false);
                } else {
                    alert('获取失败，请联系管理员');
                    $('.section-edit-form-submit').attr('disabled', true);
                }
            },
            error: function (request) {
                var message = '请稍后再试！';
                if (request.status === 404) {
                    message = '请求地址不存在，请联系管理员确认！'
                } else if(request.status >= 500) {
                    message = '请求异常，请稍后再试！'
                }
                $('.nav-edit-form-submit').attr('disabled', true);
                alert(message);
            }
        });
    });
};

var ue_generator = function () {
    if ($('#ue-container').length > 0) {
        var ue = UE.getEditor('ue-container', {
            autoHeightEnabled: false,
            autoFloatEnabled: false,
            textarea: 'cont',
            serverUrl: $('#ue-upload-url').data('url'),
            initialContent: initContent,
            initialFrameHeight: 700,
            zIndex:0,
            toolbars: [
                [
                    'source', //源代码
                    'bold', //加粗
                    'italic', //斜体
                    'underline', //下划线
                    'fontfamily', //字体
                    'fontsize', //字号
                    'paragraph', //段落格式
                    'forecolor', //字体颜色
                    'backcolor', //背景色
                    'justifyleft', //居左对齐
                    'justifyright', //居右对齐
                    'justifycenter', //居中对齐
                    'justifyjustify', //两端对齐
                    'customstyle', //自定义标题
                    'strikethrough', //删除线
                    'subscript', //下标
                    'superscript', //上标
                    'anchor', //锚点
                    'undo', //撤销
                    'redo', //重做
                    'indent', //首行缩进
                    'fontborder', //字符边框
                    'formatmatch', //格式刷
                    'blockquote', //引用
                    'pasteplain', //纯文本粘贴模式
                    'selectall', //全选
                    'preview', //预览
                    'horizontal', //分隔线
                    'removeformat', //清除格式
                    'time', //时间
                    'date', //日期
                    'unlink', //取消链接
                    'insertrow', //前插入行
                    'insertcol', //前插入列
                    'mergeright', //右合并单元格
                    'mergedown', //下合并单元格
                    'deleterow', //删除行
                    'deletecol', //删除列
                    'splittorows', //拆分成行
                    'splittocols', //拆分成列
                    'splittocells', //完全拆分单元格
                    'deletecaption', //删除表格标题
                    'inserttitle', //插入标题
                    'mergecells', //合并多个单元格
                    'deletetable', //删除表格
                    'cleardoc', //清空文档
                    'insertparagraphbeforetable', //"表格前插入行"
                    'simpleupload', //单图上传
                    'edittable', //表格属性
                    'edittd', //单元格属性
                    'link', //超链接
                    'spechars', //特殊字符
                    'searchreplace', //查询替换
                    'insertorderedlist', //有序列表
                    'insertunorderedlist', //无序列表
                    'fullscreen', //全屏
                    'directionalityltr', //从左向右输入
                    'directionalityrtl', //从右向左输入
                    'rowspacingtop', //段前距
                    'rowspacingbottom', //段后距
                    'pagebreak', //分页
                    'imagenone', //默认
                    'imageleft', //左浮动
                    'imageright', //右浮动
                    'imagecenter', //居中
                    'wordimage', //图片转存
                    'lineheight', //行间距
                    'edittip ', //编辑提示
                    'autotypeset', //自动排版
                    'touppercase', //字母大写
                    'tolowercase', //字母小写
                    'background', //背景
                    'template', //模板
                    'inserttable', //插入表格
                    'drafts', // 从草稿箱加载
                    'charts', // 图表
                ],
            ]
        });
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', $('meta[name="csrf-token"]').attr('content'))
        });
    }
};

var file_uploader = function () {
    var thumbnailContainer = $("#thumbnail-container");
    if (thumbnailContainer.length > 0) {
        thumbnailContainer.fileinput({
            maxFileCount: 1,
            language: "zh",
            dropZoneTitle: "请选择需要上传的缩略图",
            uploadUrl: thumbnailContainer.data('url'),
            defaultPreviewContent: postThumbnail === '' ? '' : '<img src="' + postThumbnail + '" alt="缩略图" height="240">',
            allowedFileExtensions: ["jpg", "png", "gif"],
        }).on('fileuploaded', function(event, file, previewId, index, reader) {
            var response = file.response;
            if (response.result) {
                $("#thumbnail").val(response.data.url)
            } else {
                alert(response.message)
            }
        }).on('fileremoved', function (event, id, index) {
            $("#thumbnail").val('');
        });
    }
};

var chunk = function (array, size) {
    var temp = [];
    for (var i = 0; i< array.length; i = i + size) {
        var tempArr = array;
        temp.push(tempArr.slice(i, i + size))
    }
    return temp;
};

var setActionIcons = function () {
    var icons = [];
    var maxIconsCountInLine = 0;
    var maxIconsDisplayLines = 6;
    var currentPage = 0;
    var lastPage = 0;
    var modalWidth = 0;
    if (typeof actionIcons !== 'undefined') {
        icons = JSON.parse(actionIcons);
        $('#setActionIconModal').on('shown.bs.modal', function () {
            $('.set-actions-icon-name').val('');
            modalWidth = $('.set-actions-icons-list-modal').width();
            maxIconsCountInLine = Math.floor(modalWidth / 38);
            pageIconsCount = maxIconsDisplayLines * (maxIconsCountInLine === 0 ? 8 : maxIconsCountInLine);
            actionIcons = chunk(icons, pageIconsCount);
            lastPage = actionIcons.length;
            appendActionIconsListHtml(actionIcons[currentPage], maxIconsCountInLine, maxIconsDisplayLines, currentPage, lastPage);
        });

        $('.set-actions-previous').on('click', function () {
            if (!$(this).hasClass('disabled')) {
                var previousPage = $(this).data('previous');
                appendActionIconsListHtml(actionIcons[previousPage], maxIconsCountInLine, maxIconsDisplayLines, previousPage, lastPage);
            }
        });

        $('.set-actions-next').on('click', function () {
            if (!$(this).hasClass('disabled')) {
                var nextPage = $(this).data('next');
                appendActionIconsListHtml(actionIcons[nextPage], maxIconsCountInLine, maxIconsDisplayLines, nextPage, lastPage);
            }
        });

        $('.set-actions-icon-name').on('input propertychange', function () {
            var words = $(this).val();
            var resultIcons = searchIconsArray(words, icons);
            if (resultIcons.length > 0) {
                actionIcons = chunk(resultIcons, pageIconsCount);
                lastPage = actionIcons.length;
                appendActionIconsListHtml(actionIcons[currentPage], maxIconsCountInLine, maxIconsDisplayLines, currentPage, lastPage);
            } else {
                appendActionIconsListHtml([], maxIconsCountInLine, maxIconsDisplayLines, -1, 0);
            }
        });
    }
};

var appendActionIconsListHtml = function (icons, iconsCountInLine, maxIconsDisplayLines, currentPage, lastPage) {
    var html = '<tr>';
    var maxDisplayIcons = maxIconsDisplayLines * iconsCountInLine;
    var maxIconsCountInLine = iconsCountInLine === 0 ? 8 : iconsCountInLine;
    var displayCount = icons.length > maxDisplayIcons ? maxDisplayIcons : icons.length;
    var lastIconIndex = displayCount - 1;
    var selectedIcon = $('.set-action-icon-value').val();
    for (var i = 0; i < displayCount; i++) {
        if (selectedIcon === icons[i]) {
            html += '<td><button class="btn btn-warning set-actions-icon-button" data-icon="' + icons[i] + '" onclick="selectActionIcon($(this));">';
        } else {
            html += '<td><button class="btn btn-default set-actions-icon-button" data-icon="' + icons[i] + '" onclick="selectActionIcon($(this));">';
        }
        html += '<i class="fa ' + icons[i] + '" aria-hidden="true"></i></button></td>';
        if (((i+1) % maxIconsCountInLine === 0) || i === lastIconIndex) {
            html += '</tr>';
            if (i !== lastIconIndex) {
                html += '<tr>';
            }
        }
    }
    var addOn = maxIconsCountInLine - (i % maxIconsCountInLine);
    if (addOn !== 0 && (addOn !== maxIconsCountInLine || i === 0)) {
        if (i !== 0) {
            html = html.substring(0, html.length - 5);
        }
        for (var j = 0; j < addOn; j++) {
            html += '<td></td>';
        }
        html += '</tr>';
    }
    $('.set-actions-icons-list > tbody').html(html);
    var paginate = $('.set-actions-page-info');
    paginate.attr('colspan', maxIconsCountInLine - 2);
    $('.set-actions-icon-label').parent('td').attr('colspan', maxIconsCountInLine);
    var previousBtn = $('.set-actions-previous');
    var nextBtn = $('.set-actions-next');
    if (currentPage <= 0) {
        if (!previousBtn.hasClass('disabled')) {
            previousBtn.addClass('disabled')
        }
    } else {
        if (previousBtn.hasClass('disabled')) {
            previousBtn.removeClass('disabled')
        }
    }
    if (currentPage >= (lastPage - 1)) {
        if (!nextBtn.hasClass('disabled')) {
            nextBtn.addClass('disabled')
        }
    } else {
        if (nextBtn.hasClass('disabled')) {
            nextBtn.removeClass('disabled')
        }
    }
    paginate.html((currentPage + 1) + ' / ' + lastPage);
    previousBtn.data('previous', currentPage - 1);
    nextBtn.data('next', currentPage + 1);
};

var searchIconsArray =  function(str, container) {
    var nPos;
    var vResult = [];

    for(var i in container){
        var sTxt=container[i]||'';
        nPos=sTxt.indexOf(str);
        if(nPos>=0){
            vResult[vResult.length] = sTxt;
        }
    }
    return vResult;
};

var selectActionIcon = function (e) {
    $('.set-action-icon').html('<i class="fa ' + e.data('icon') + '" aria-hidden="true"></i>');
    $('.set-action-icon-value').val(e.data('icon'));
    if (!e.hasClass('btn-warning')) {
        e.removeClass('btn-default');
        e.parents('tbody').find('.btn').each(function (index, element) {
            if ($(element).hasClass('btn-warning')) {
                $(element).removeClass('btn-warning');
                $(element).addClass('btn-default');
            }
        });
        e.addClass('btn-warning');
    }
};

var roleActionsCheckboxRelate = function () {
    $('.parentRoleAction').on('click', function () {
        if ($(this).is(':checked')) {
            // 勾选，将所有子菜单勾选
            $(this).parents('table').find('.childRoleAction').each(function (index, element) {
                $(element).prop('checked', true);
            });
        } else {
            // 移除勾选，移除所有子菜单的勾选状态
            $(this).parents('table').find('.childRoleAction').each(function (index, element) {
                $(element).prop('checked', false);
            });
        }
    });

    $('.childRoleAction').on('click', function () {
        if ($(this).is(':checked')) {
            // 子菜单勾选，则触发父级菜单勾选
            $(this).parents('table').find('.parentRoleAction').prop('checked', true);
        } else {
            // 子菜单移除勾选，判断当前子菜单勾选数量，如果为0，则移除父级菜单的勾选
            var table = $(this).parents('table');
            if (table.find('.childRoleAction:checked').length === 0) {
                table.find('.parentRoleAction').prop('checked', false);
            }
        }
    });
};

var departmentsListSelect = function () {
    var treeA = $('.tree-menu > li > a');
    if (typeof (currentDID) !== 'undefined') {
        treeA.each(function (index, element) {
            if ($(element).data('d-id') === parseInt(currentDID)) {
                $(element).parent('li').addClass('active');
            }
        })
    }
    treeA.on('click', function () {
        $('.tree-menu > li').removeClass('active');
        $(this).parent('li').addClass('active');
        if ($(this).parents('.departments-tree-menu').length > 0) {
            getDepartmentInfo($(this));
        } else if($(this).parents('.contacts-departments').length > 0) {
            getDepartmentUsers($(this));
        } else {
            var selectParentDepartmentModal = $('#selectParentDepartmentModal');
            if(selectParentDepartmentModal.length > 0) {
                selectParentDepartmentModal.modal('hide');
                $('input[name="parent_id"]').val($(this).data('d-id'));
                $('#parentDepartmentName').val($(this).children('.department-name').html());
                return true;
            }
            var selectUserDepartmentModal = $('#selectUserDepartmentModal');
            if (selectUserDepartmentModal.length > 0) {
                selectUserDepartmentModal.modal('hide');
                $('input[name="dep_id"]').val($(this).data('d-id'));
                $('#departmentName').val($(this).children('.department-name').html());
                return true;
            }
        }
    });
};

var getDepartmentInfo = function (element) {
    $.ajax({
        cache: false,
        type: 'GET',
        url: $('.departments-tree-menu').data('url'),
        async: false,
        data: {'id': element.data('d-id')},
        success: function (data) {
            if (data.status) {
                $('#name').val(data.data.name);
                $('#parentDepartmentName').val(data.data.parent_name);
                $('input[name="parent_id"]').val(data.data.parent_id);
                $('#weight').val(data.data.weight);
                $('#des').val(data.data.des);
                $('input[name="id"]').val(data.data.id);
                $('.selectParentDepartmentButton').prop('disabled', false)
            } else {
                var html = '<div class="alert alert-danger alert-dismissable">';
                html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                html += data.message + '</div>';
                $('.alert-area').html(html)
            }
        },
        error: function (request) {
            var message = '请稍后再试！';
            if (request.status === 404) {
                message = '请求地址不存在，请联系管理员确认！'
            } else if(request.status >= 500) {
                message = '请求异常，请稍后再试！'
            }
            var html = '<div class="alert alert-danger alert-dismissable">';
            html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            html += message + '</div>';
            $('.alert-area').html(html);
        }
    });
};

var deleteDepartment = function () {
    $('.delete-department').on('click', function () {
        var li = $('.departments-tree-menu').find('li[class="active"]');
        if (li.length === 0) {
            var html = '<div class="alert alert-danger alert-dismissable">';
            html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            html += '请选择要删除的部门！</div>';
            $('.alert-area').html(html);
            return false;
        } else {
            if (confirm('该操作将一同删除该部门的下级部门，请确认！')) {
                var id = '';
                li.each(function (index, element) {
                    id = '?id=' + $(element).children('a').data('d-id');
                });
                $(this).attr('href', $(this).attr('href') + id);
                return true;
            } else {
                return false;
            }
        }
    });
};


$(document).ready(function () {
    ajax_init();
    select2_init();
    tr_pointer_click();
    ue_generator();
    file_uploader();
    setActionIcons();
    roleActionsCheckboxRelate();
    departmentsListSelect();
    deleteDepartment();
});