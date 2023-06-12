{include file="public/header" /}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>添加用户</title>
    <style>
        .layui-table-cell {
  /* 设置表格单元格的宽度和高度 */
  width: 80px;
  height: 40px;
  text-align: center;
  font-size: 22px;
}
    </style>
</head>

<body>
<div class="layuimini-container">
    <div class="layuimini-main">

	
		
            <table lay-filter="dataTb" id="idDatas"></table>
    </div>	
</div>		
	
</body>
<script>

var table;
			layui.use(['table','element'], function(){
				var element = layui.element;
				table = layui.table;
				table.render({
					id:'idData'
					,elem: '#idDatas'
					,height: 'full-50'
					,skin:'row'
					,even:true
					,cellMinWidth: 130
					,page: true
					,limit:20
					,limits:[20,40,60]
					,url:"{$config['admin_route']}api/get_history_log_listdata?type=0" //数据接口
					,cols: [
							[ //表头
								{field:'id', align:'center', fixed:'left', title:'序号', width:60}
                                ,{field:'userid', align:'center', title:'操作用户'}
								,{field:'action', align:'center', title:'日志'}
								,{field:'TerminalIP', align:'center', title:'IP'}
								,{field:'actionTime', align:'center', title:'时间'}
                                
							]
						]
				});
			});
</script>
</html>