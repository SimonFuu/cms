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

$(document).ready(function () {
    ajax_init();
    select2_init();
    tr_pointer_click();
    ue_generator();
    file_uploader();
});