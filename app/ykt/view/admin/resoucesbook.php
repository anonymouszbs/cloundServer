{include file="public/header" /}

<!doctype html>
<html>

<body>
    <div class="layuimini-container">
        <div class="layuimini-main">
            <fieldset class="table-search-fieldset">
                <legend style="font-size:20px;color:#1aa094!important;">教材管理</legend>
                <div style="margin: 10px 10px 10px 10px">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">

                            <div class="layui-inline">
                                <label class="layui-form-label">标题</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="city" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-inline">
                                <button type="submit" class="layui-btn layui-btn-primary" lay-submit lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                            </div>
                        </div>
                    </form>
                </div>
            </fieldset>


            <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>

            <script type="text/html" id="toolbarDemo">
                <div class="layui-btn-container">
                    <button class="layui-btn layui-btn-sm data-add-btn" lay-event="add">添加教材</button>
                </div>
            </script>
            <script type="text/html" id="currentTableBar">
                <a class="layui-btn layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
                <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="sign">删除</a>
            </script>
            <script type="text/html" id="switchTpl">
                <input type="checkbox" name="isstudyplan" value="{{d.ietm_id}}" lay-skin="switch" lay-text="正常|关闭" lay-filter="isstudyplan" {{ d.isstudyplan == 1 ? 'checked' : '' }}>
            </script>
            <script>
                layui.use(['form', 'table'], function() {
                    var $ = layui.jquery,
                        form = layui.form,
                        table = layui.table,
                        layuimini = layui.layuimini;

                    table.render({
                        type: "get",
                        elem: '#currentTableId',
                        url: '{$config["admin_route"]}admin/get_resources',
                        toolbar: '#toolbarDemo',
                        defaultToolbar: ['filter', 'exports', 'print', {
                            title: '提示',
                            layEvent: 'LAYTABLE_TIPS',
                            icon: 'layui-icon-tips'
                        }],
                        cols: [
                            [ //表头
                                {
                                    field: 'index',
                                    align: 'center',
                                    fixed: 'left',
                                    title: '序号',
                                    width: '5%',
                                    sort: true
                                }, {
                                    field: 'ietm_name',
                                    title: '标题',
                                    width: '15%'
                                }, {
                                    field: 'authorid',
                                    align: 'center',
                                    title: '创建者',
                                    width: '15%'
                                }, {
                                    field: 'Dimension1',
                                    align: 'center',
                                    title: '维度1',
                                    width: '10%',
                                    sort: true
                                },
                                {
                                    field: 'Dimension2',
                                    align: 'center',
                                    title: '维度2',
                                    width: '10%',
                                    sort: true
                                },
                                {
                                    field: 'parentnodeid',
                                    align: 'center',
                                    title: '教材目录',
                                    width: '10%',
                                    sort: true
                                },
                                {
                                    field: 'updateTime',
                                    align: 'center',
                                    title: '更新时间',
                                    width: '10%',
                                    sort: true
                                }, {
                                    align: 'center',
                                    field: 'isstudyplan',
                                    title: '学习计划',
                                    width: '10%',
                                    templet: '#switchTpl',
                                    unresize: true
                                }, {
                                    align: 'center',
                                    toolbar: '#currentTableBar',
                                    title: '操作',
                                    fixed: 'right',
                                    width: '15%'
                                }
                            ]
                        ],
                        limit: 22,
                        page: true,
                        height: 'full-200'
                    });
                    table.on('toolbar(currentTableFilter)', function(obj) {
                        if (obj.event === "add") {
                            var index = layer.open({
                                title: '提示：如果未提交，所有的数据表和资源都不会进行保存。',
                                type: 2,
                                shade: 0.2,
                                maxmin: true,
                                shadeClose: true,
                                area: ['100%', '100%'],
                                content: '{$config["admin_route"]}admin/addbook',
                            });
                            $(window).on("resize", function() {
                                layer.full(index);
                            });
                            return false;

                        }  
                    });
                    //启用学习计划
                    form.on('switch(isstudyplan)', function(obj) {
                        var index = layer.load(0, {
                            shade: false
                        });
                        $.ajax({
                            type: 'POST',
                            data: {
                                ietm_id: this.value
                            },
                            dataType: 'json',
                            url: '{$config["admin_route"]}Admin/start_isplan',
                            success: function(data) {
                                layer.close(index);
                                var code = data.code;
                                var msg = data.msg;
                                switch (code) {
                                    case 1:
                                        layer.msg(msg, {
                                            icon: 5,
                                            shade: false
                                        });
                                        break;
                                    default:
                                        layer.alert(msg, {
                                            icon: 6
                                        });
                                }
                            }
                        });
                    });
                    // 监听搜索操作
                    form.on('submit(data-search-btn)', function(data) {
                        var result = JSON.stringify(data.field);
                        var js1 = JSON.parse(result);
                        table.reload('currentTableId', {
                            url: '{$config["admin_route"]}admin/search_resources?ietm_name=' + js1.city,
                            methods: 'get',
                            toolbar: '#toolbarDemo',
                            page: {
                                curr: 1
                            }
                        }, 'data');

                        return false;
                    });

                    //监听按钮提交
                    table.on('tool(currentTableFilter)', function(obj) {
                        
                        var data = obj.data;
                        
                        if (obj.event === 'edit') {
                            //location.href="file_info.php?id="+data.id
                            // layer.msg('查看');

                            var href = "{$config['admin_route']}admin/bookedit?ietm_id=" + data.ietm_id;
                            layer.open({
                                type: 2,
                                title: '详情',
                                skin: 'layui-layer-rim',
                                shadeClose: true,
                                shade: 0.2,
                                area: ['85%', '85%'],
                                content: href,
                                yes: function(index, layero) {
                                    layer.close(index);
                                }
                            });
                        }else if (obj.event == 'sign') {

                            layer.msg('是否确认删除？', {
                                time: 0,
                                btn: ['确认', '取消'],
                                yes: function(index) {
                                    $.ajax({
                                        url: "{$config['admin_route']}admin/deletebook?item_id="+data.ietm_id,
                                        data: {

                                        },
                                        type: 'post',
                                        dataType: "json",
                                        success: function(data) {
                                            var code = data.code;
                                            var msg = data.msg;
                                            switch (code) {
                                                case 1:
                                                    layer.msg(data.msg);
                                                    table.reload('currentTableId');
                                                    break;
                                                case 0:
                                                    layer.msg(data.msg);
                                                    break;
                                                default:
                                                    break;
                                            }
                                        },
                                        error: function(data) {
                                            layer.msg(data.msg);
                                        }
                                    });
                                    layer.close(index);
                                }

                            });




                        }
                    });
                });
            </script>
        </div>
    </div>


</body>

</html>