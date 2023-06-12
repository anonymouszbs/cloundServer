{include file="public/header" /}
<!doctype html>
<html>

<head>
    <title>添加书籍</title>
    <link href="https://unpkg.com/@wangeditor/editor@latest/dist/css/style.css" rel="stylesheet">
    <style>
        .list-container {
            height: 300px;

            overflow: scroll;

        }

        .list-container #list li {
            display: flex;
            align-items: center;
        }

        .list-container #list li.selected {
            background-color: #f0f0f0;
        }

        .list-container #list li span {
            width: 40px;
            height: 38px;
            background-color: transparent;
            text-align: center;
            line-height: 38px;
        }

        input::selection {
            background-color: blue;
            color: white;
        }

        #editor—wrapper {
            border: 1px solid #ccc;
            z-index: 100;
            /* 按需定义 */
        }

        #toolbar-container {
            border-bottom: 1px solid #ccc;
        }

        #editor-container {
            height: 500px;
        }
    </style>

</head>


<body>
    <div class="layuimini-container">
        <div class="layuimini-main">
            <label for="bookname" class="layui-form-label">
                书籍名称
            </label>
            <div class="layui-input-block" style="width:200px; display: flex;">
                <input type="text" id="bookname" name="bookname" value="" lay-verify="required" lay-vertype="tips" autocomplete="off" class="layui-input">
                <button class="layui-btn layui-btn-sm data-add-btn" id = "saveEpub" style="height:38px;" lay-event="saveEpub"> 保存 </button>
            </div>
            <p style="color:crimson">所有内容创建完毕后，先点击保存，后点击确认创建。否则会报错！</p>
            <div class="layui-row">

                <div class="layui-col-md5" id="left-form">
                    <div class="layui-card">
                        <div class="layui-card-header"><i class="fa fa-calendar-o icon icon-blue"></i>章节列表</div>
                        <div class="layui-card-body">
                            <table id="currentTableId" lay-filter="currentTableId"></table>

                        </div>
                    </div>
                </div>
                <div class="layui-col-md7" id="right-form">
                    <div class="layui-card">
                        <div class="layui-card-header"><i class="fa fa-database icon icon-blue"></i>章节内容</div>
                        <div class="layui-card-body">


                            <div id="editor" style="margin: 0px 0 0px 0">
                                <div id="editor—wrapper">
                                    <div id="toolbar-container"><!-- 工具栏 --></div>
                                    <div id="editor-container"><!-- 编辑器 --></div>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>

            <!-- </form> -->

        </div>
    </div>
    <input id="filepath" style="display:none"/>
    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm data-add-btn" lay-event="addchaptertitle"> 添加章节 </button>
        </div>
    </script>
    <script type="text/html" id="currentTableBar">
        <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">移除</a>
        <a class="layui-btn layui-btn-sm data-add-btn" type="button">选择本章</a>
    </script>
    <script src="https://unpkg.com/@wangeditor/editor@latest/dist/index.js"></script>
    <script type="text/javascript">
        var editor1, editor;
        var data = [];
        var innerHTML = [];
        var index = 1;
        var currentChapterIndex = 0;
        var resdata;
        layui.use(['form', 'table', 'layedit'], function() {
            var $ = layui.jquery,
                form = layui.form,
                table = layui.table,
                layuimini = layui.layuimini,
                element = layui.element,
                layer = layui.layer,
                layedit = layui.layedit;

            table.render({
                elem: '#currentTableId',
                height: 'full-20',
                data: [],
                limit: 100,
                toolbar: '#toolbarDemo',
                defaultToolbar: ['filter', 'exports', 'print', {
                    title: '提示',
                    layEvent: 'LAYTABLE_TIPS',
                    icon: 'layui-icon-tips'
                }],
                cols: [
                    [{
                            field: 'index',
                            align: 'center',
                            fixed: 'left',
                            title: '序号',
                            width: '10%',
                            sort: true
                        }, {
                            field: 'title',
                            align: 'center',
                            title: '章节标题',
                            width: '65%',
                            edit: 'text',
                            sort: true
                        },
                        {
                            field: 'id',
                            align: 'center',
                            title: '操作',
                            width: '25%',
                            templet: '#currentTableBar',
                            sort: true
                        }
                    ]
                ],
            });
            
            $("#saveEpub").on("click",function(){
                var jdata = {
                    "bookname":$('#bookname').val(),
                    "data":data
                };
                $.post("{$config['admin_route']}Api/saveEpub", jdata, function(res) {
                    if(res.code==1){
                        layer.msg('保存成功，请点击确认创建！');
                        $('#filepath').val(res);
                        resdata = res;
                    }else{
                        layer.msg('保存失败');
                        
                    }
                }, 'json');
            });
            layui.table.on("row(currentTableId)", function(obj) {

                console.log(obj);
                $(".layui-table-body .layui-table tr").attr({
                    "style": "background:#FFF"
                }); //其它tr恢复原样（必须在前）
                $(obj.tr.selector).attr({
                    "style": "background:#EEE"
                }); //改变当前tr颜色（必须在后）

                // 附加功能：选中行自动勾选上
                obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
                obj.tr.find('i[class="layui-anim layui-icon"]').trigger("click");
                var jdata = obj.data;
                currentChapterIndex = jdata.index - 1;
                // console.log(innerHTML[currentChapterIndex]);
                // editor.setHtml(innerHTML[currentChapterIndex]);
                if (editor.isDisabled()) editor.enable();
                if (!editor.isFocused()) editor.focus();

                editor.select([]);
                editor.deleteFragment();


                editor.dangerouslyInsertHtml(data[currentChapterIndex]["value"]);

            });
            table.on('toolbar(currentTableId)', function(obj) {
                if (obj.event === "addchaptertitle") {
                    data = table.cache['currentTableId'];
                    var array = {
                        "index": data.length + 1,
                        "title": "第" + (data.length + 1) + "章",
                        "value": null,
                        "images": [],
                        "videos": [],
                        "id": data.length
                    };
                    data.push(array);
                    table.reload('currentTableId', {
                        data: data
                    });
                    return false;

                }
            });
            table.on('tool(currentTableId)', function(obj) {
                var jdata = obj.data;
                if (obj.event == "delete") {
                    // 	obj.del();
                    data = table.cache['currentTableId'];
                    data.splice(jdata.index - 1, 1);
                    for (var i in data) {
                        data[i]['index'] = ++i;
                    }
                    table.reload('currentTableId', {
                        data: data
                    });
                }
            });
            const {
                createEditor,
                createToolbar
            } = window.wangEditor

            const editorConfig = {
                placeholder: 'Type here...',
                onChange(editor) {
                    var html = editor.getHtml()
                    console.log(editor.getText());
                    data[currentChapterIndex]["value"] = html;
                    // 也可以同步到 <textarea>
                },
                MENU_CONF: {
                    uploadImage: {
                        server: "{$config['admin_route']}admin/epubUploadImg",
                        onSuccess(file, res) { // TS 语法
                            // onSuccess(file, res) {          // JS 语法
                            console.log(`${file.name} 上传成功`, res.data);
                            var html = editor.getHtml()

                            data[currentChapterIndex]["value"] = html;
                            
                            
                            data[currentChapterIndex]["images"].push(res.data.url);
                        },
                        // fieldName: 'your-fileName',
                        // base64LimitSize: 10 * 1024 * 1024 // 10M 以下插入 base64
                    },
                    uploadVideo: {
                        server: "{$config['admin_route']}admin/epubUploadVideo",
                        onSuccess(file, res) { // TS 语法
                            // onSuccess(file, res) {          // JS 语法
                            
                            
                            
                            data[currentChapterIndex]["videos"].push(res.data.url);
                        },
                    }

                },
            }

            editor = createEditor({
                selector: '#editor-container',
                html: '<p><br></p>',
                config: editorConfig,
                mode: 'default', // or 'simple'
            })

            const toolbarConfig = {}

            const toolbar = createToolbar({
                editor,
                selector: '#toolbar-container',
                config: toolbarConfig,
                mode: 'default', // or 'simple'
            })


        });
    </script>

</body>

</html>