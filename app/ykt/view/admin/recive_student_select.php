{include file="public/header" /}
<!doctype html>
<html>

<head>
    <title>选择学生</title>
</head>

<div style="width:100%;float:left;margin:0 1%;">
    <table lay-filter="dataTb" id="idDatas"></table>
</div>



<script type="text/javascript">
    layui.use(['laydate', 'laypage', 'layer', 'table', 'carousel', 'upload', 'element'], function() {
        var laydate = layui.laydate //日期
            ,
            laypage = layui.laypage //分页
        layer = layui.layer //弹层
            , table = layui.table //表格
            , carousel = layui.carousel //轮播
            , upload = layui.upload //上传
            , element = layui.element; //元素操作


        //初始化表格
        table.render({
            id: 'idData',
            elem: '#idDatas',
            height: 'full-0',
            cellMinWidth: 300,
            page: false,
            
            defaultToolbar: ['', '', ''],
            url: "{$config['admin_route']}admin/get_studenlist" //数据接口
                ,
            cols: [
                [ //表头
                    {
                        type: 'checkbox',
                        fixed: 'left'
                    }, {
                        field: 'index',
                        align: 'center',
                        title: '序号',
                        width: '20%'
                    }, {
                        field: 'name',
                        align: 'center',
                        title: '接收人',
                        width: '80%'
                    }
                    //,{align: 'center', toolbar: '#barDemo', title:'操作', fixed: 'right', width:'30%'}
                ]
            ]
        });


    });
</script>