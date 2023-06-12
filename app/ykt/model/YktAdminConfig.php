<?php
namespace app\ykt\model;
use app\ykt\model\Base;

/**
* 配置表
*/

class YktAdminConfig extends Base{
	protected $name = 'system_admin_config'; //设置数据表名
    public function getAll(){
		$aList = static::where('config_status',1)->order('config_sort DESC')->select()->toArray();
		if(empty($aList)){
			return [];
		}else{
			$return = [];
			foreach($aList as $k=>$v){
				$return[$v['config_name']] = $v['config_value'];
			}
		}
		return $return;
	}
	public function updateAll($data){
		$lists = static::order('config_sort DESC,config_id')->select()->toArray();
		if(empty($lists)){
			return false;
		}else{
			foreach($lists as &$lists_v){
				$lists_v['config_value'] = $data[$lists_v['config_name']];
			}
			$save = static::saveAll($lists);
			if(empty($save)){
				return false;
			}
		}
		return true;
	}
}

?>