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
                <label for="username" class="layui-form-label">
                    用户名
                </label>
                <div class="layui-input-block">
                    <input type="text" id="username" name="username" value="" lay-verify="required" lay-vertype="tips" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="user_nick" class="layui-form-label">
                    用户昵称
                </label>
                <div class="layui-input-block">
                    <input type="text" id="user_nick" name="user_nick" value="" lay-verify="required" lay-vertype="tips" autocomplete="off" class="layui-input">
                </div>
            </div>

            <!-- 编辑时不显示密码框 -->
            <div class="layui-form-item">
                <label for="pwd" class="layui-form-label">
                    用户密码
                </label>
                <div class="layui-input-block">
                    <input type="text" id="pwd" name="pwd" lay-verify="required" lay-vertype="tips" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="WorkPermitNum" class="layui-form-label">
                    工作证号码
                </label>
                <div class="layui-input-block">
                    <input type="text" id="WorkPermitNum" name="WorkPermitNum" value="" lay-verify="required" lay-vertype="tips" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="id_card_number" class="layui-form-label">
                    身份证号码
                </label>
                <div class="layui-input-block">
                    <input type="text" id="id_card_number" name="id_card_number" value="" lay-verify="required" lay-vertype="tips" autocomplete="off" class="layui-input">
                </div>
            </div>
           

            <div class="layui-form-item" pane="">
                <label class="layui-form-label">
                    状态
                </label>
                <div class="layui-input-block">
                    <input type="radio" name="AllowedLogin" value="1" title="正常" checked="checked">
                    <input type="radio" name="AllowedLogin" value="0" title="锁定">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    角色
                </label>
                <div class="layui-input-block">
                    <select name="role" xm-select="role-select" lay-verify="required" lay-vertype="tips">
                        <option value="">请选择角色</option>
                        {volist name="lbmc" id="vo"}
                        <option value="{$vo['id']}">{$vo['lbmc']}</option>
                        {/volist}
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    部门
                </label>
                <div class="layui-input-block">
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

                    </select>
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
                $.post("{$config['admin_route']}Api/insertUser", data.field, function(res) {
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