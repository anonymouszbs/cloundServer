<?php
namespace app\ykt\controller;




use think\facade\Db;

use think\facade\View;
use think\facade\Cookie;


class Login{
    public function index(){
     
       
        return View::fetch();
    }
    public function login(){
       
        $account = trim(input('post.account'));
        if(empty($account)){
            echo json_encode(["code"=>0,"msg"=>"请输入账户"]);
            exit;
        }
        $password = md5(trim(input('post.pw')));
        if(empty($password)){
            echo json_encode(["code"=>0,"msg"=>"请输入密码"]);
            exit;
        }
        $code = trim(input('post.code'));
        if(empty($code)){
            echo json_encode(["code"=>0,"msg"=>"请输入验证码"]);
            exit;
        }
        //判断验证码

        if(!captcha_check($code)){
            echo json_encode(["code"=>0,"msg"=>"验证码错误"]);
            exit;
        }
       
        $user = Db::table('ykt_user')->where('username', $account)->find();
                
            if ($user && $password==$user['pwd']) { 
               
                Cookie::set('admin_id',$user['id']);
                
                Cookie::set('admin_name',$user['username']);
                echo json_encode(["code"=>1,"msg"=>"登陆成功","href"=>"/index.php/ykt/index/index"]);
                return;
           
            } else {
                echo json_encode(["code"=>0,"msg"=>"账号或密码错误"]);
               
            }
       
    }
}
