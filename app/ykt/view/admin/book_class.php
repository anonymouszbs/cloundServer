{include file="public/header" /}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>目录权限</title>
</head>

<body>

    <div class="layuimini-container">
        <div class="layuimini-main">

            <div>

                <table id="munu-table" class="layui-table" lay-filter="munu-table"></table>
            </div>
        </div>
    </div>
    <!--添加 -->
    <script type="text/html" id="toolbar">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm data-add-btn" lay-event="add">添加教材分类</button>
        </div>
    </script>
    <!-- 操作列 -->
    <script type="text/html" id="auth-state">
        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">修改</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script>
        layui.use(['table', 'treetable'], function() {
            var $ = layui.jquery;
            var table = layui.table;
            var treetable = layui.treetable;

            // 渲染表格
            layer.load(2);
            treetable.render({
                treeColIndex: 1,
                treeSpid: -1,
                treeIdName: 'id',
                treePidName: 'ParentID',
                elem: '#munu-table',
                toolbar: '#toolbar',
                url: '{$config["admin_route"]}api/ykt_content_tree',
                page: false,
                cols: [
                    [{
                            type: 'numbers'
                        },
                        {
                            field: 'NodeName',
                            minWidth: 200,
                            title: '权限名称'
                        },
                        {
                            field: 'sortid',
                            width: 80,
                            align: 'center',
                            title: '排序号'
                        },
                        {
                            templet: '#auth-state',
                            width: 120,
                            align: 'center',
                            title: '操作'
                        }
                    ]
                ],
                done: function() {
                    layer.closeAll('loading');
                }
            });
            table.on('toolbar(munu-table)', function(obj) {
                if (obj.event === "add") {
                    var index = layer.open({
                        title: '修改',
                        type: 2,
                        shade: 0.2,
                        maxmin: true,
                        shadeClose: true,
                        area: ['60%', '60%'],
                        content: '{$config["admin_route"]}admin/add_book_class',
                    });
                    $(window).on("resize", function() {
                        layer.full(index);
                    });
                    return false;

                }
            });


            $('#btn-expand').click(function() {
                treetable.expandAll('#munu-table');
            });

            $('#btn-fold').click(function() {
                treetable.foldAll('#munu-table');
            });

            //监听工具条
            table.on('tool(munu-table)', function(obj) {
                var data = obj.data;
                var layEvent = obj.event;

                if (layEvent === 'edit') {
                    var index = layer.open({
                        title: '更新',
                        type: 2,
                        shade: 0.2,
                        maxmin: true,
                        shadeClose: true,
                        area: ['60%', '60%'],
                        content: '{$config["admin_route"]}admin/up_book_class?id=' + data.id,
                    });
                    $(window).on("resize", function() {
                        layer.full(index);
                    });
                } else if (layEvent === 'del') {
                    layer.msg('是否确认删除？', {
                        time: 0,
                        btn: ['确认', '取消'],
                        yes: function(index) {
                            $.post("{$config['admin_route']}Api/del_content_tree?id=" + data.id, data.field, function(res) {
                                layer.close(index);
                                var code = res.code;
                                var msg = res.msg;
                                switch (code) {
                                    case 1:
                                        layer.alert(msg, {
                                            icon: 6,
                                            shade: false
                                        });
                                        window.setTimeout(function() {
                                            window.location.reload();
                                        }, 2000);
                                        break;
                                    default:
                                        layer.alert(msg, {
                                            icon: 5,
                                            shade: false
                                        });
                                        break;
                                }
                            }, 'json');
                            layer.close(index);
                        }

                    });

                }
            });
        });
    </script>
</body>

</html>