<?php

namespace app\ykt\controller;

use think\App;
use think\facade\Db;
use think\facade\View;
use think\facade\Cookie;

class Index extends Base
{
    public function index()
    {
        //echo 1;exit;

        return View::fetch();
    }
    public function welcome()
    {
        //echo 1;exit;
        return View::fetch();
    }
    /**退出登录*/
    
    public function loginout(){
      Cookie::delete('admin_id');
      Cookie::delete('admin_name');
      echo json_encode(array(
        'code'=>1,'msg'=>'退出成功'
      ));
      return;
    }
    /**获取目录接口 */
    public function getMenu()
    {
        $array = json_decode('
        {
            "homeInfo": {
              "title": "首页",
              "href": "/index.php/ykt/index/welcome"
            },
            "logoInfo": {
              "title": "云课堂",
              "image": "/static/admin/images/logo.ico",
              "href": ""
            },
            "menuInfo": [
              {
                "title": "常规管理",
                "icon": "fa fa-address-book",
                "href": "",
                
                "child": [
                 
                              
                ]
              }
            ]
          } ');
        $where = [[
            'status', '=', 1
        ]];

        $menus = Db::table("ykt_menu")->order('sort','asc')->where($where)->select();
        foreach ($menus as $menus_v) {
            if ($menus_v['parent_id'] == 0) {
                $menu[$menus_v['id']] = $menus_v;
            } else {
                $menu[$menus_v['parent_id']]['child'][] = $menus_v;
            }
        }
        foreach ($menu as $key=>$value) {
           
            array_push($array->menuInfo[0]->child, $value);
            
        }
        // array_push($array->menuInfo[0]->child, $menu);
        echo json_encode($array);
        exit;
    }
    public function getMenu_nosort()
    {
      header('content-type:application/json');
        $array = array();

        $menus = Db::table("ykt_menu")->select();
        foreach ($menus as $menus_v) {
          
          $jarray =  $menus_v;
          $jarray["parent_id"] = $menus_v["parent_id"]==0?-1:$jarray["parent_id"] ;
          array_push($array, $jarray);
        }
        // array_push($array->menuInfo[0]->child, $menu);
        echo json_encode(array('code'=>0,'count'=>19,'data'=>$array));
        exit;
    }
}
