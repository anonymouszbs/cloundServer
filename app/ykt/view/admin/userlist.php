{include file="public/header" /}

<!doctype html>
<html>

<body>
    <div class="layuimini-container">
        <div class="layuimini-main">
            <fieldset class="table-search-fieldset">
                <legend style="font-size:20px;color:#1aa094!important;">用户管理</legend>
                <div style="margin: 10px 10px 10px 10px">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">

                            <div class="layui-inline">
                                <label class="layui-form-label">用户名</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="username" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">部门</label>
                                <div class="layui-input-inline">
                                    <select id="Department" name="Department" lay-filter="Department">
                                        <option value="">请选择</option>
                                        {volist name="department" id="item"}

                                        <optgroup value={$item.id} label="{$item.DepartmentName}">
                                            {volist name="$item.children" id="child"}
                                            <option value={$child.id}>{$child.DepartmentName}</option>
                                            {/volist}
                                        </optgroup>
                                        {/volist}

                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">角色</label>
                                <div class="layui-input-inline">
                                    <select name="role" xm-select="role-select" >
                                        <option value="">请选择角色</option>
                                        {volist name="lbmc" id="vo"}
                                        <option value="{$vo['id']}">{$vo['lbmc']}</option>
                                        {/volist}
                                    </select>
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
                    <button class="layui-btn layui-btn-sm data-add-btn" lay-event="add">添加用户</button>
                </div>
            </script>
            <script type="text/html" id="currentTableBar">
                <a class="layui-btn layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
                <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="sign">删除</a>
            </script>
            <script type="text/html" id="switchTpl">
                <input type="checkbox" name="AllowedLogin" value="{{d.id}}" lay-skin="switch" lay-text="正常|关闭" lay-filter="AllowedLogin" {{ d.AllowedLogin == 1 ? 'checked' : '' }}>
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
                        url: '{$config["admin_route"]}Api/get_user_list',
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
                                    field: 'username',
                                    title: '用户名',
                                    width: '15%'
                                }, {
                                    field: 'role',
                                    align: 'center',
                                    title: '角色',
                                    width: '10%',
                                    sort: true
                                },
                                {
                                    field: 'WorkPermitNum',
                                    align: 'center',
                                    title: '工作证号码',
                                    width: '10%',
                                    sort: true
                                },
                                {
                                    field: 'Department',
                                    align: 'center',
                                    title: '部门',
                                    width: '15%',
                                    sort: true
                                },
                                {
                                    field: 'LoginState',
                                    align: 'center',
                                    title: '登录状态',
                                    width: '10%',
                                    sort: true
                                },
                                {
                                    field: 'create_time',
                                    align: 'center',
                                    title: '创建时间',
                                    width: '10%',
                                    sort: true
                                }, {
                                    align: 'center',
                                    field: 'AllowedLogin',
                                    title: '授权状态',
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
                                content: '{$config["admin_route"]}admin/adduser',
                            });
                            $(window).on("resize", function() {
                                layer.full(index);
                            });
                            return false;

                        }
                    });
                    //启用用户授权
                    form.on('switch(AllowedLogin)', function(obj) {
                        var index = layer.load(0, {
                            shade: false
                        });
                        $.ajax({
                            type: 'POST',
                            data: {
                                id: this.value
                            },
                            dataType: 'json',
                            url: '{$config["admin_route"]}Api/isAllowedLogin',
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
                            url: '{$config["admin_route"]}Api/search_user_list',
                            methods: 'post',
                            where: js1,
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

                            var href = "{$config['admin_route']}admin/user_edit_info?id=" + data.id;
                            layer.open({
                                type: 2,
                                title: '编辑用户',
                                skin: 'layui-layer-rim',
                                shadeClose: true,
                                shade: 0.2,
                                area: ['85%', '85%'],
                                content: href,
                                yes: function(index, layero) {
                                    layer.close(index);
                                }
                            });
                        } else if (obj.event == 'sign') {

                            layer.msg('是否确认删除？', {
                                time: 0,
                                btn: ['确认', '取消'],
                                yes: function(index) {
                                    $.ajax({
                                        url: "{$config['admin_route']}Api/del_user?id=" + data.id,
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