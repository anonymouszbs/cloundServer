
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>云课堂-后台管理</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/static/plugs/layui-v2.6.3/css/layui.css" media="all">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden
        }

        body {
            background: #000000;
        }

        body:after {
            content: '';
            background-repeat: no-repeat;
            background-size: cover;
            -webkit-filter: blur(3px);
            -moz-filter: blur(3px);
            -o-filter: blur(3px);
            -ms-filter: blur(3px);
            filter: blur(3px);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        .layui-container {
            width: 100%;
            height: 100%;
            overflow: hidden
        }

        .admin-login-background {
            width: 360px;
            height: 300px;
            position: absolute;
            left: 50%;
            top: 40%;
            margin-left: -180px;
            margin-top: -100px;
        }

        .logo-title {
            text-align: center;
            letter-spacing: 2px;
            padding: 14px 0;
        }

        .logo-title h1 {
            color: #009688;
            font-size: 25px;
            font-weight: bold;
        }

        .login-form {
            background-color: #000000;
            border: 1px solid #009688;
            border-radius: 3px;
            padding: 14px 20px;
            box-shadow: 0 0 8px #009688;
        }

        .login-form .layui-form-item {
            position: relative;
        }

        .login-form .layui-form-item label {
            position: absolute;
            left: 1px;
            top: 1px;
            width: 38px;
            line-height: 36px;
            text-align: center;
            color: #d2d2d2;
        }

        .login-form .layui-form-item input {
            padding-left: 36px;
        }

        .captcha {
            width: 60%;
            display: inline-block;
        }

        .captcha-img {
            display: inline-block;
            width: 34%;
        }

        .captcha-img img {
            height: 34px;
            border: 1px solid #e6e6e6;
            height: 36px;
            width: 100%;
        }

        .layui-input,
        .layui-select,
        .layui-textarea {
            background-color: #000000;
            color: #2cffeb
        }
    </style>
</head>

<body>
    <div class="layui-container">
        <div class="admin-login-background">

            <div class="layui-form login-form">

                <form class="layui-form" name="f" method="post" >
                    <div class="layui-form-item logo-title">
                        <!--<img src="style/images/logo.png" alt="logo" width="50">-->
                        <h1>云课堂-后台管理</h1>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-icon layui-icon-username" for="username"></label>
                        <input type="text" name="user" lay-verify="required|account" placeholder="用户名" autocomplete="off" class="layui-input" value="">
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-icon layui-icon-password" for="password"></label>
                        <input type="password" name="pw" lay-verify="required|password" placeholder="密码" autocomplete="off" class="layui-input" value="">
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-icon layui-icon-vercode" for="captcha"></label>
                        <input type="text" name="captcha" lay-verify="required|captcha" placeholder="图形验证码" autocomplete="off" class="layui-input verification captcha" value="">
                        <div class="captcha-img">
                            <img id="captchaPic" src="{:captcha_src()}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <input type="checkbox" name="rememberMe" value="true" lay-skin="primary" title="记住密码">
                    </div>
                    <div class="layui-form-item">
                        <button type="button"  class="layui-btn layui-btn-fluid" lay-submit="" onsubmit="Submit" lay-filter="login">登 入</button>
                    </div>
                   
                        
                 
                </form>
            </div>
        </div>
    </div>
    <script src="/style/layui/layui.all.js" charset="utf-8"></script>
    <script src="/static/plugs/layui-v2.6.3/layui.js" charset="utf-8"></script>
    <script src="/static/style/js/public.js" charset="utf-8"></script>
    <script src="/static/plugs/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
    <script src="/static/plugs/jq-module/jquery.particleground.min.js" charset="utf-8"></script>

    <script>
        $('#captchaPic').click(function() {
            $(this).attr('src', '{:captcha_src()}?rand=' + Math.random());
        });
        $(document).ready(function() {
            $('.layui-container').particleground({
                dotColor: '#5cbdaa',
                lineColor: '#5cbdaa'
            });
        });
        layui.use('layer', function() {
				//非空验证
				$('button[type="button"]').click(function() {
					var account = $('input[name="user"]').val();
					var pw = $('input[name="pw"]').val();
					var code = $('input[name="captcha"]').val();
					if(account == '') {
						ErrorAlert('请输入您的账号');
					} else if(pw == '') {
						ErrorAlert('请输入密码');
					} else if(code == '' || code.length != 4) {
						ErrorAlert('输入验证码');
					} else {
						//登陆
						var JsonData = {
							account: account,
							pw: pw,
							code: code
						};
                       // $('form').serialize()
						$.post("/index.php/ykt/Login/login",JsonData,function(res) {
						    switch (res.code) {
                                case 0:
                                    ErrorAlert(res.msg);
                                    break;
                                case 1:
                                    OkAlert(res.msg);
                                    setTimeout(() => {
                                        window.location.href = res.href;
                                    }, 1000);
                                    break;
                                default:
                                    break;
                            }
						//window.location.href = 'http://127.0.0.1:8020/jQueryLogin/index.html?__hbt=1567408106021';
						},"json");						
					}
				});
			});
    
    </script>
</body>

</html>