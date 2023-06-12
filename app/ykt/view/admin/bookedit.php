{include file="public/header" /}
<!doctype html>
<html>

<head>
    <title>添加书籍</title>
</head>

<body>


    <div class="layuimini-container">
        <div class="layuimini-main">
            <div class="layui-row">
                <div class="layui-col-md6" id="left-form">
                    <div class="layui-card">
                        <div class="layui-card-header"><i class="fa fa-calendar-o icon icon-blue"></i>基础信息</div>
                        <div class="layui-card-body">
                            <form class="layui-form layui-form-pane" action="" lay-filter="thisForm">
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="width: 200px;">封面</label>


                                    <div class="layui-input-block" style="margin-left: 200px;">
                                        <div class="layui-upload">
                                            <input type="hidden" id="imgfilepath">
                                            <button type="button" class="layui-btn layui-btn-primary" id="filepath_img" style="width: 208px;"><i class="layui-icon"></i>选择图片</button><input class="layui-upload-file" type="file" accept="" name="imgfile">
                                            图片支持jpg|png|gif|jpeg|bmp最大10MB
                                            <img id="showimg" style="display:block;width: 108px;height:108px;  position: absolute; top: 0%; left:600px;" src="{$selectdata['Thumbnail']}">
                                        </div>

                                    </div>


                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="width: 200px;">创建用户</label>
                                    <div class="layui-input-inline">
                                        <select name="authorid" id="authorid" lay-filter="authorid" lay-verify="authorid">
                                            <option value="{$aUser['id']}">{$aUser['username']}</option>
                                        </select>
                                    </div>
                                    <label class="layui-form-label" style="width: 200px;">教材目录</label>
                                    <div class="layui-input-inline">
                                        <select name="parentnodeid" id="parentnodeid" lay-filter="parentnodeid" lay-verify="parentnodeid">
                                            {volist name="parentnodeidlist" id="vo"}
                                            <option value="{$vo['id']}">{$vo['NodeName']}</option>
                                            {/volist}
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="width: 200px;">教材名称</label>
                                    <div class="layui-input-inline">
                                        <input type="text" lay-verify="required" id="ietm_name" name="ietm_name" class="layui-input" style="width:200px;" autocomplete="off">
                                    </div>
                                    <label class="layui-form-label" style="width: 200px;">教材版本号</label>
                                    <div class="layui-input-inline">
                                        <input type="text" lay-verify="required" id="Version" name="Version" class="layui-input" style="width:200px;" autocomplete="off">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="width: 200px;">维度1</label>
                                    <div class="layui-input-inline">
                                        <select name="Dimension1" id="Dimension1" lay-filter="Dimension1" lay-verify="Dimension1">
                                            {volist name="dimension1list" id="vo"}
                                            <option value="{$vo['id']}">{$vo['lbmc']}</option>
                                            {/volist}
                                        </select>

                                    </div>
                                    <label class="layui-form-label" style="width: 200px;">维度2</label>
                                    <div class="layui-input-inline">
                                        <select name="Dimension2" lay-filter="Dimension2" lay-verify="Dimension2">
                                            {volist name="dimension2list" id="vo"}
                                            <option value="{$vo['id']}">{$vo['lbmc']}</option>
                                            {/volist}
                                        </select>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="width: 200px">seo标签</label>
                                    <div class="layui-input-inline">
                                        <input placeholder="注释{多个标签使用，号隔开}" type="text" lay-verify="KeyWord" id="KeyWord" name="KeyWord" class="layui-input" style="width:200px;" autocomplete="off">
                                    </div>
                                    <label class="layui-form-label" style="width: 200px">创建日期</label>
                                    <div class="layui-input-inline">
                                        <input type="text" lay-verify="CreateTime" name="CreateTime" id="CreateTime" class="layui-input" value="2021-12-25" autocomplete="off" lay-key="1">
                                    </div>

                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label" style="width: 200px">下发学习计划</label>
                                    <div class="layui-input-inline">
                                        <select name="isstudyplan" lay-filter="isstudyplan" id="isstudyplan" lay-verify="isstudyplan">
                                            <option value="0">否</option>
                                            <option value="1">是</option>

                                        </select>
                                    </div>
                                    <div style="display:none;" id="showRecive" class="showRecive">
                                        <div class="layui-form-mid layui-word-aux">可选[个人]</div>
                                        <div class="layui-input-inline" id="getunitshow" style="width: 300px;">
                                            <button class="layui-btn layui-btn-normal layui-btn-radius" id="selectunit"><i class="layui-icon"></i>选择接收人</button>
                                            <div class="layui-form-mid layui-word-aux">已选择 <font color="red" id="unitnum" value="">
                                                </font> 个接收人</div>
                                        </div>
                                    </div>
                                </div>


                                <!-- <div class="layui-form-item">
                                    <label class="layui-form-label" style="width: 200px">文件</label>
                                    <div class="layui-input-block" style="margin-left: 200px;">
                                        <div class="layui-upload">
                                            <input type="hidden" id="filepath">
                                            <button type="button" class="layui-btn layui-btn-primary" id="filepath_select" style="width: 208px;margin-right:6px"><i class="layui-icon"></i>选择文件</button><input class="layui-upload-file" type="file" accept="" name="file">
                                            <button type="button" class="layui-btn layui-btn-normal layui-btn-radius" id="filepath_upload"><i class="layui-icon"></i>上传</button>
                                            <button type="button" class="filepath layui-btn layui-btn-primary layui-btn-radius" style="display:none;"><i class="fa fa-check-circle"></i> <a id="b_filepath" href="" target="_blank">点击下载</a></button>
                                        </div>
                                    </div>
                                    <div style="display:none; width:406px;" lay-showpercent="true" id="progress" class="layui-progress">
                                        <div class="layui-progress-bar layui-bg-bar" id="jindu" lay-percent="0%"></div>
                                    </div>
                                    <div class="layui-form-mid layui-word-aux">文件支持epub|wav|wma|flac|mp4|avi|mkv|doc|pdf格式，最大200MB</div>
                                </div> -->
                                <div class="layui-form-item layui-form-text" style="width:800px;">
                                    <label class="layui-form-label">简介</label>
                                    <div class="layui-input-block">
                                        <textarea name="Introduction" id="Introduction" lay-verify="" placeholder="请输入书籍简介" class="layui-textarea" autocomplete="off"></textarea>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="submit">更新教材</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md6" id="right-form">
                    <div class="layui-card">
                        <div class="layui-card-header"><i class="fa fa-database icon icon-blue"></i>文件管理</div>
                        <div class="layui-card-body">
                            <div class="layui-btn-container">

                                <button class="layui-btn layui-btn-sm data-add-btn">上传学习资源</button>
                                <button class="layui-btn layui-btn-sm data-add-btn-edit" lay-event="createpub" id="createpub">创建可编辑资源</button>
                            </div>
                            <table class="layui-table" id="file-list" lay-filter="file-list"></table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="applyuser" value="applyuser">
            <input type="hidden" id="filename" value="filename">
            <input type="hidden" id="filesize" value="filesize">
            <input type="hidden" id="fileattr" value="fileattr">

            <!-- <input type="hidden" id="unitnamestr" value="unitnamestr"> -->
            <input type="hidden" id="reciveuser" value="">
            <!-- <input type="hidden" id="usernamestr" value="usernamestr"> -->
            <!-- <input type="hidden" id="useridstr2" value="useridstr2"> -->
            <!-- <input type="hidden" id="usernamestr2" value="usernamestr2"> -->

        </div>
    </div>


    <script type="text/html" id="editbarDemo">
        <div class="layui-btn-container">
            {{# if(d.edit == 1){ }}
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑内容</a>
                {{# } else { }}
                    <a class="layui-btn layui-btn-normal layui-btn-xs">不可编辑</a>
                    {{# } }}
                        <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">删除</a>

        </div>
    </script>
    <script type="text/html" id="dowbar">
        <div class="layui-btn-container">
            {{# if(d.res == 1){ }}
                <a class="layui-btn layui-btn-normal layui-btn-xs" href="{{d.FilePath}}">点击下载</a>
                {{# } else { }}
                    <a class="layui-btn layui-btn-normal layui-btn-xs">等待上传</a>
                    {{# } }}
        </div>
    </script>

    <script>
        var site;
        var uploading = false;

        var jdata = JSON.parse('<?php echo $filedata; ?>');
        var fileindex = jdata.data.length;
        console.log(jdata);
        let uploaddata = {
            code: 0,
            data: jdata.data

        }

        function base64ToBinary(base64) {
            const binaryString = atob(base64);
            const len = binaryString.length;
            const bytes = new Uint8Array(len);
            for (let i = 0; i < len; ++i) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return bytes;
        }

        layui.use(['laydate', 'laypage', 'layer', 'table', 'carousel', 'upload', 'element', 'form', 'laydate', 'jquery'], function() {
            var laydate = layui.laydate //日期
                ,
                laypage = layui.laypage //分页
            layer = layui.layer //弹层
                , table = layui.table //表格
                , carousel = layui.carousel //轮播
                , upload = layui.upload //上传
                , form = layui.form, $ = layui.jquery, element = layui.element; //元素操作
            laydate.render({
                elem: '#CreateTime',
                trigger: 'click' //采用click弹出
            });
            laydate.render({
                elem: '#deadtime',
                trigger: 'click' //采用click弹出
            });
            table.render({
                elem: '#file-list',

                cols: [
                    [{
                            align: 'center',
                            fixed: 'right',
                            width: '5%',
                            field: 'id',
                            title: '序号',
                            sort: true
                        },
                        {

                            align: 'center',
                            fixed: 'right',
                            width: '30%',
                            field: 'name',
                            title: '文件名称'
                        },
                        {
                            align: 'center',
                            fixed: 'right',
                            width: '10%',
                            field: 'FilePath',
                            toolbar: '#dowbar',
                            title: '下载'
                        },
                        {
                            align: 'center',
                            fixed: 'right',
                            width: '20%',
                            field: 'size',
                            title: '大小'
                        },
                        {
                            align: 'center',
                            fixed: 'right',
                            width: '20%',
                            field: 'type',
                            title: '类型'
                        },
                        {
                            align: 'center',
                            fixed: 'right',
                            width: '15%',
                            toolbar: '#editbarDemo',
                            title: '操作'
                        }
                    ]
                ],
                data: uploaddata.data
            });

            $('#showimg').attr('src', "/{$selectdata['Thumbnail']}");

            $('#parentnodeid').val("{$selectdata['parentnodeid']}");
            $('#authorid').val("{$selectdata['authorid']}");
            $('#ietm_name').val("{$selectdata['ietm_name']}");
            $('#Version').val("{$selectdata['Version']}");
            $('#Dimension1').val('{$selectdata["Dimension1"]}');
            $('#KeyWord').val('{$selectdata["KeyWord"]}');
            $('#CreateTime').val('{$selectdata["CreateTime"]}');

            $('#isstudyplan').val('{$selectdata["isstudyplan"]}');

            $("#reciveuser").val('{$selectdata["reciveuser"]}');
            //图片地址
            $('#imgfilepath').val('{$selectdata["Thumbnail"]}');
            $reciverslength = '{$selectdata["reciveuser"]}'.split('|').length;
            lay("#unitnum")[0].innerHTML = ($reciverslength);

            '{$selectdata["isstudyplan"]}' == 1 ? $('.showRecive').show() : $('.showRecive').hide();
            $("#Introduction").val('{$selectdata["Introduction"]}');

            form.render('select');
            // $selectdata['Dimension1']

            // $('#Dimension1').find("option[value=]").attr("selected",true);


            // $('#authorid').val('$selectdata["parentnodeid"]');
            // $('#authorid').val('$selectdata["parentnodeid"]');
            // $('#authorid').val('$selectdata["parentnodeid"]');

            $("#createpub").on('click', function() {
                layer.open({
                    type: 2,
                    title: '在线创建epub书籍',
                    shade: 0.2,
                    btn: ['确认创建'],
                    skin: 'layui-layer-rim',
                    shadeClose: true,
                    area: ['80%', '80%'],
                    content: "{$config['admin_route']}admin/add_edit_epub",
                    yes: function(index, layero) {



                        var body = layer.getChildFrame('body', index);
                        var iframeWin = window[layero.find('iframe')[0]['name']];
                        var inputEl = iframeWin;
                        // 获取 input 元素的值

                        var inputValue = inputEl.resdata;
                        if (inputValue == null) {
                            layer.msg('请先点击保存', {
                                icon: 7
                            });

                        } else if (inputEl.data.leng == 0) {
                            layer.msg('请先创建内容，然后点击保存，再点击确认创建', {
                                icon: 7
                            });
                        } else {
                            layer.close(index);
                            var jdata = {
                                id: ++fileindex,
                                name: inputValue.name,
                                size: inputValue.size,
                                type: inputValue.type,
                                edit: 1,
                                file: inputValue.file,
                                res: 0,
                                "data": inputEl.data,

                            }

                            uploaddata.data.push(jdata);
                            fileindex = uploaddata.data.length;
                            table.reload('file-list', {
                                data: uploaddata.data
                            });
                        }


                    }
                });
            });
            table.on('tool(file-list)', function(obj) {
                var data = obj.data;
                switch (obj.event) {
                    case 'delete':
                        layer.msg('是否确认删除资源？', {
                            time: 0,
                            btn: ['确认', '取消'],
                            yes: function(index) {
                                if (data.res === 0) {
                                    uploaddata.data = table.cache['file-list'];
                                    uploaddata.data = uploaddata.data.filter(item => item.id !== data.id);
                                    uploaddata.data = uploaddata.data.map((item, index) => {
                                        item.id = index + 1;
                                        return item;
                                    });
                                    fileindex = uploaddata.data.length;

                                    table.reload('file-list', {
                                        data: uploaddata.data
                                    });
                                } else if (data.res === 1) {
                                    uploaddata.data = table.cache['file-list'];
                                    uploaddata.data = uploaddata.data.filter(item => item.id !== data.id);
                                    uploaddata.data = uploaddata.data.map((item, index) => {
                                        item.id = index + 1;
                                        return item;
                                    });
                                    fileindex = uploaddata.data.length;

                                    table.reload('file-list', {
                                        data: uploaddata.data
                                    });
                                    $.ajax({
                                        url: "{$config['admin_route']}admin/deleteResource?id=" + data.sid,
                                        data: {},
                                        type: 'post',
                                        dataType: "json",
                                        success: function(data) {
                                            var code = data.code;
                                            var msg = data.msg;
                                            switch (code) {
                                                case 1:
                                                    layer.msg(data.msg);

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
                                }


                                layer.close(index);
                            }

                        });


                        break;
                    case "edit":
                        if (data.res == 1) {
                            layer.open({
                                type: 2,
                                title: '在线修改epub书籍',
                                shade: 0.2,
                                btn: ['确认创建'],
                                skin: 'layui-layer-rim',
                                shadeClose: true,
                                area: ['80%', '80%'],
                                content: "{$config['admin_route']}admin/save_edit_epub?bookname=" + data.name + "&code=" + encodeURI(JSON.stringify(data.data)),
                                yes: function(index, layero) {
                                    var body = layer.getChildFrame('body', index);
                                    var iframeWin = window[layero.find('iframe')[0]['name']];
                                    var inputEl = iframeWin;
                                    // 获取 input 元素的值
                                    var inputValue = inputEl.resdata;
                                    if (inputValue == null) {
                                        layer.msg('请先点击保存', {
                                            icon: 7
                                        });

                                    } else if (inputEl.data.leng == 0) {
                                        layer.msg('请先创建内容，然后点击保存，再点击确认创建', {
                                            icon: 7
                                        });
                                    } else {
                                        layer.close(index);
                                        var dataj = {
                                            FileName: inputValue.name,
                                            FilePath: inputValue.file,
                                            data: inputEl.data,
                                            Size: inputValue.size,
                                        }
                                        $.post("{$config['admin_route']}api/upEpubInfo?id=" + data.sid, dataj, function(res) {
                                            if (res.code == 1) {
                                                layer.msg("更新成功");
                                                var jdata = {
                                                    id: data.id,
                                                    name: inputValue.name,
                                                    size: inputValue.size,
                                                    type: inputValue.type,
                                                    edit: 1,
                                                    res:1,
                                                    file: inputValue.file,
                                                    "data": inputEl.data,
                                                }

                                                uploaddata.data[data.id - 1].name =inputValue.name ;
                                                uploaddata.data[data.id - 1].size =inputValue.size ;
                                                uploaddata.data[data.id - 1].file =inputValue.file ;
                                                uploaddata.data[data.id - 1].data = inputEl.data ;
                                                fileindex = uploaddata.data.length;
                                                table.reload('file-list', {
                                                    data: uploaddata.data
                                                });
                                            } else {
                                                layer.msg("更新失败");
                                            }
                                        }, 'json');

                                    }
                                }
                            });
                        } else if(data.res==0) {
                            layer.open({
                            type: 2,
                            title: '在线修改epub书籍',
                            shade: 0.2,
                            btn: ['确认创建'],
                            skin: 'layui-layer-rim',
                            shadeClose: true,
                            area: ['80%', '80%'],
                            content: "{$config['admin_route']}admin/save_edit_epub?bookname=" + data.name + "&code=" + encodeURI(JSON.stringify(data.data)),
                            yes: function(index, layero) {
                                var body = layer.getChildFrame('body', index);
                                var iframeWin = window[layero.find('iframe')[0]['name']];
                                var inputEl = iframeWin;
                                // 获取 input 元素的值
                                var inputValue = inputEl.resdata;
                                if (inputValue == null) {
                                    layer.msg('请先点击保存', {
                                        icon: 7
                                    });

                                } else if (inputEl.data.leng == 0) {
                                    layer.msg('请先创建内容，然后点击保存，再点击确认创建', {
                                        icon: 7
                                    });
                                } else {
                                    layer.close(index);
                                    var jdata = {
                                        id: data.id,
                                        name: inputValue.name,
                                        size: inputValue.size,
                                        type: inputValue.type,
                                        edit: 1,
                                        res: 0,
                                        file: inputValue.file,
                                        "data": inputEl.data,

                                    }

                                    uploaddata.data[data.id-1] = jdata;
                                    fileindex = uploaddata.data.length;
                                    table.reload('file-list', {
                                        data: uploaddata.data
                                    });
                                }
                            }
                        });
                        }
                        break;
                    default:
                        break;
                }

            });
            upload.render({
                elem: '.data-add-btn', // 绑定元素
                accept: 'file',
                url: '{$config["admin_route"]}admin/falseupload',
                exts: 'epub|wav|wma|flac|mp4|avi|mkv|doc|pdf|docx',
                auto: false,
                choose: function(obj) {
                    // 获取文件信息

                    obj.preview(function(index, file, result) {

                        const parts = file.name.split('.');
                        const extension = parts[parts.length - 1];
                        var jdata = {
                            id: ++fileindex,
                            name: file.name,
                            size: file.size,
                            type: extension,
                            edit: 0,
                            file: result,
                            res: 0
                        }
                        uploaddata.data.push(jdata);
                        fileindex = uploaddata.data.length;
                        table.reload('file-list', {
                            data: uploaddata.data
                        });

                    });



                },
                done: function(res) {

                },

                error: function() { // 上传失败回调函数
                    $('#previewText').html('上传失败');
                }
            });


            //添加资源
            $('.data-add-btn').on('click', function() {

            });
            //选择接收学生
            $(document).on('click', '#selectunit', function() {

                layer.open({
                    type: 2,
                    title: '请选择接收学生',
                    shade: 0.2,
                    btn: ['提交选择'],
                    skin: 'layui-layer-rim',
                    shadeClose: true,
                    area: ['40%', '80%'],
                    content: "{$config['admin_route']}admin/recive_student_select",
                    yes: function(index, layero) {
                        var body = layer.getChildFrame('body', index);
                        var iframeWin = window[layero.find('iframe')[0]['name']]
                        var checkStatus = iframeWin.layui.table.checkStatus('idData');
                        if (checkStatus.data.length > 0) {
                            var selectedData = checkStatus.data[0];
                            var getUserList = "";
                            for (var i = 0; i < checkStatus.data.length; i++) {
                                getUserList = getUserList + checkStatus.data[i].id + "|";
                            }
                            $("#reciveuser").val(getUserList);
                            // lay('#footer')[0].innerHTML =
                            lay("#unitnum")[0].innerHTML = (checkStatus.data.length);
                            //alert(selectedData.name)
                            layer.close(index)
                            console.log('调试阶段');
                            return false;
                        } else {
                            layer.msg("请选择接收人");
                        }
                    }
                });

                return false;
            });
            //是否上传学习计划
            form.on('select(isstudyplan)', function(data) {
                if (data.value == 0) {
                    $('.showRecive').hide();
                } else {
                    $('.showRecive').show();
                }
            });
            //这是图片上传
            upload.render({
                elem: '#filepath_img', // 绑定元素
                url: "{$config['admin_route']}admin/uploadImg", // 上传接口地址
                accept: 'jpg|png|gif|jpeg|bmp', // 允许上传的文件类型
                done: function(res) { // 上传成功回调函数
                    var code = res.code;
                    var msg = res.msg;
                    switch (code) {
                        case 1:
                            layer.msg(msg);
                            $('#showimg').show();
                            $('#showimg').attr('src', res.src);
                            $('#imgfilepath').val(res.src);

                            break;
                        default:
                            layer.msg(msg);;
                    }

                },

                error: function() { // 上传失败回调函数
                    $('#previewText').html('上传失败');
                }
            });
            //选完文件后不自动上传 这是文件上传
            upload.render({
                elem: '#filepath_img1',
                url: "{$config['admin_route']}admin/upload",
                accept: 'file',
                exts: 'epub|wav|wma|flac|mp4|avi|mkv|doc|pdf',
                auto: false,
                bindAction: '#filepath_upload',
                choose: function(obj) {
                    // 获取文件信息
                    obj.preview(function(index, file, result) {
                        var jdata = {
                            name: file.name,
                            size: file.size,
                            type: '1',
                            file: file.file
                        }
                        uploaddata.data.push(jdata);
                        table.reload('file-list');
                    });


                },
                done: function(res) {
                    var code = res.code;
                    var msg = res.msg;
                    switch (code) {
                        case 1:
                            layer.msg('上传成功');
                            $('.filepath').show();
                            $('#b_filepath').attr('href', res.src);
                            $('#filepath').val(res.src);
                            $('#fileattr').val(res.fileattr);

                            break;
                        default:
                            layer.msg(msg);;
                    }
                },
                progress: function(n) {
                    var percent = n + "%";
                    $('#progress').show();
                    $('#jindu').attr('lay-percent', percent);
                    element.render();
                }
            });

            //提交表格
            form.on('submit(submit)', function(data) {
                console.log(data);
                data.field.filepath = $('#filepath').val();
                data.field.fileattr = $('#fileattr').val();
                data.field.filename = $('#filename').val();
                data.field.filesize = $('#filesize').val();
                //删除代码data.field.unitnamestr = $('#unitnamestr').val();
                data.field.reciveuser = $('#reciveuser').val();
                data.field.Thumbnail = $('#imgfilepath').val();

                if (data.field.iscollect == 1) {
                    if (data.field.deadtime == '') {
                        layer.msg('请选择截止日期');
                        return false;
                    }
                }
                //这是接收人判断
                // if (data.gettype == 1) {
                //     if (data.reciveuser == '') {
                //         layer.msg('请至少选择一个接收人');
                //         return false;
                //     }
                // }
                if (data.field.filepath == '') {
                    layer.msg('请上传文件');
                    return false;
                }
                var dataj = {
                    authorid: data.field.authorid,
                    parentnodeid: data.field.parentnodeid,
                    ietm_name: data.field.ietm_name,
                    Version: data.field.Version,
                    Dimension1: data.field.Dimension1,
                    Dimension2: data.field.Dimension2,
                    KeyWord: data.field.KeyWord,
                    CreateTime: data.field.CreateTime,
                    isstudyplan: data.field.isstudyplan,
                    Introduction: data.field.Introduction,
                    reciveuser: data.field.reciveuser,
                    Thumbnail: data.field.Thumbnail,
                    filelistdata: uploaddata.data,

                };
                var index = layer.load(1, {
                    shade: false
                });
                $.post("{$config['admin_route']}admin/updatebookresource?ietm_id=" + "{$selectdata['ietm_id']}", dataj, function(res) {
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