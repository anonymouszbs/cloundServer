{include file="public/header" /}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>添加用户</title>
</head>

<body>
    <div class="yadmin-body animated fadeIn">
        <form class="layui-form layui-form-pane" action="" lay-filter="thisForm">
            <div class="layui-form-item">
                <label for="NodeName" class="layui-form-label">
                分类名称
                </label>
                <div class="layui-input-block">
                    <input type="text" id="NodeName" name="NodeName" value="" lay-verify="required" lay-vertype="tips" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="ParentID" class="layui-form-label">
                类别id
                </label>
                <div class="layui-input-block">
                
                    <select name="ParentID" xm-select="role-select" lay-verify="required" lay-vertype="tips">
                        <option value="0">默认顶级分类</option>
                        {volist name="select" id="vo"}
                        <option value="{$vo['id']}">{$vo['NodeName']}</option>
                        {/volist}
                    </select>
                </div>
            </div>

            <!-- 编辑时不显示密码框 -->
            <div class="layui-form-item">
                <label for="sortid" class="layui-form-label">
                    排序id
                </label>
                <div class="layui-input-block">
                    <input type="text" id="sortid" name="sortid" lay-verify="required" lay-vertype="tips" autocomplete="off" class="layui-input">
                </div>
            </div>
            
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="submit">增加</button>
                </div>

            </div>



        </form>
    </div>


    <script>
        layui.use(['form', 'tree'], function() {
            var form = layui.form;
            var tree = layui.tree;
            var layer = layui.layer;
            $ = layui.jquery;
            form.on('submit(submit)', function(data) {
                console.log(data.field);

                var index = layer.load(1, {
                    shade: false
                });
                $.post("{$config['admin_route']}Api/add_content_tree", data.field, function(res) {
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

                return false;
            });
        });
    </script>

</body>

</html>