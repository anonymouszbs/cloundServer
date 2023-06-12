<?php

namespace app\ykt\controller;


use app\ykt\model\YktAdminConfig;
use think\App;
use think\facade\Db;
use think\facade\Session;
use think\facade\View;
use think\facade\Cookie;

class Base
{

    public $adminId = null;
    public $config = [];
    //账户权限逻辑
    public function __construct()
    {

        date_default_timezone_set('PRC');
        #获取系统配置

        $YktAdminConfig = new YktAdminConfig();
        $this->config = $YktAdminConfig->getAll();
       

        # 获取账户，账户判断是否登录
        $this->adminId = Cookie::get('admin_id');
        
        if (
            empty($this->adminId)
        ) {
          
            header('Location:' . $this->config['admin_route'] . 'Login');
            exit;
            
        }

		

        $aUser = Db::table('ykt_user')->where('id', $this->adminId)->find();
        // if (empty($aUser)) {
		// 	Cookie::delete('admin_id');
		// 	$this->error('管理员账户不存在');
		// }
		// if ($aUser['status'] != 1) {
		// 	Cookie::delete('admin_id');
		// 	$this->error('管理员已被禁用');
		// }

        View::assign([
            'aUser' => $aUser,
            'config' => $this->config
        ]);
    }
    
}