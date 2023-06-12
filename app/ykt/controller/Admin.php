<?php

namespace app\ykt\controller;

use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Request;
use think\facade\View;
use think\facade\Cookie;
use Webapi;

class Admin extends Base
{

    function dir_copy($src = '', $dst = '')
    {
        if (empty($src) || empty($dst))
        {
            return false;
        }
     
        $dir = opendir($src);
        self::dir_mkdir($dst);
        while (false !== ($file = readdir($dir)))
        {
            if (($file != '.') && ($file != '..'))
            {
                if (is_dir($src . '/' . $file))
                {
                    self::dir_copy($src . '/' . $file, $dst . '/' . $file);
                }
                else
                {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
     
        return true;
    }
     
     
    /**
     * 创建文件夹
     *
     * @param string $path 文件夹路径
     * @param int $mode 访问权限
     * @param bool $recursive 是否递归创建
     * @return bool
     */
    function dir_mkdir($path = '', $mode = 0777, $recursive = true)
    {
        clearstatcache();
        if (!is_dir($path))
        {
            mkdir($path, $mode, $recursive);
            return chmod($path, $mode);
        }
     
        return true;
    }
    function deleteFolder($folder) {
        if (is_dir($folder)) {
            $files = scandir($folder);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $path = $folder . '/' . $file;
                    if (is_dir($path)) {
                        self::deleteFolder($path);
                    } else {
                        unlink($path);
                    }
                }
            }
            rmdir($folder);
        }
    }
    /**更新教材分类页面 */
    public function up_book_class(){
        $id = request()->get('id');
        $selectdata = Db::table("ykt_ietm_content_tree")->where('id',$id)->find();
        $select = Db::table("ykt_ietm_content_tree")->select();
        View::assign([
            'select' => $select,
            'selectdata'=>$selectdata
        ]);
        return View::fetch();
    }
    /**添加教材分类页面 */
    public function add_book_class(){
        $select = Db::table("ykt_ietm_content_tree")->select();
        View::assign([
            'select' => $select,
        ]);
        return View::fetch();
    }
    /**教材分类页面 */
    public function book_class(){
        return View::fetch();
    }
    /**目录权限页面 */
    public function directory_right(){
        return View::fetch();
    }
    /**历史操作记录页面 */
    public function history_log() {
        return View::fetch();
    }
    /**违规记录页面 */
    public function illegal_log() {
        return View::fetch();
    }
    
    //添加epub可编辑页面
    public function add_edit_epub()
    {
        $user = Cookie::get("admin_name");
        if(is_dir("storage/uploads/$user/epubwork/")){
            self::deleteFolder("storage/uploads/$user/epubwork/");
        }
        
        $endpath = "storage/uploads/$user/epubwork/";
        $startpath = "work";
        self::dir_copy($startpath,$endpath);

        return View::fetch();
    }

    //编辑epub
    public function save_edit_epub()
    {
        $code = request()->get('code');
        $code = urldecode($code);
        $user = Cookie::get("admin_name");
        if(is_dir("storage/uploads/$user/epubwork/")){
            self::deleteFolder("storage/uploads/$user/epubwork/");
        }
        
        $endpath = "storage/uploads/$user/epubwork/";
        $startpath = "work";
        self::dir_copy($startpath,$endpath);
        
        
        View::assign([
            'code' => $code,
            'bookname'=>request()->get('bookname')

        ]);
        return View::fetch();
    }


    /**用户管理页面 */
    public function userlist()
    {
        $lbmc = Db::name('ykt_lb')->where('dzbm', 'role')->select();
        $where = [[
            'status', '=', 1
        ]];

        $department = Db::table("YKT_department")->where($where)->select();

        foreach ($department as $index => $menus_v) {
            if ($menus_v['ParentDepartmentID'] == 0) {
                $menu[$menus_v['id']] = $menus_v;
            } else {
                $menu[$menus_v['ParentDepartmentID']]['children'][] = $menus_v;
            }
        }
        $array = array('data' => []);
        foreach ($menu as $key => $value) {

            array_push($array['data'], $value);
        }

        View::assign([
            'lbmc' => $lbmc,
            'department' => $array['data'],

        ]);
        return View::fetch();
    }
    /**用户编辑页面 */
    public function user_edit_info()
    {
        $where = [[
            'status', '=', 1
        ]];
        $id = request()->get('id') ? request()->get('id') : 1;

        $selectdata = Db::name('ykt_user')->where('id', $id)->find();


        $rolelist = Db::name('ykt_lb')->where('dzbm', 'role')->select();

        $department = Db::table("YKT_department")->where($where)->select();

        foreach ($department as $index => $menus_v) {
            if ($menus_v['ParentDepartmentID'] == 0) {
                $menu[$menus_v['id']] = $menus_v;
            } else {
                $menu[$menus_v['ParentDepartmentID']]['children'][] = $menus_v;
            }
        }
        $array = array('data' => []);
        foreach ($menu as $key => $value) {

            array_push($array['data'], $value);
        }


        View::assign([
            'selectdata' => $selectdata,
            'rolelist' => $rolelist,
            'department' => $array['data'],

        ]);
        return View::fetch();
    }
    /**添加用户页面 */
    public function adduser()
    {
        $lbmc = Db::name('ykt_lb')->where('dzbm', 'role')->select();
        $where = [[
            'status', '=', 1
        ]];

        $department = Db::table("YKT_department")->where($where)->select();

        foreach ($department as $index => $menus_v) {
            if ($menus_v['ParentDepartmentID'] == 0) {
                $menu[$menus_v['id']] = $menus_v;
            } else {
                $menu[$menus_v['ParentDepartmentID']]['children'][] = $menus_v;
            }
        }
        $array = array('data' => []);
        foreach ($menu as $key => $value) {

            array_push($array['data'], $value);
        }

        View::assign([
            'lbmc' => $lbmc,
            'department' => $array['data'],

        ]);
        return View::fetch();
    }
    static function convertKeys($obj)
    {
        foreach ($obj as $key => $value) {
            if ($key === 'DepartmentName') {
                $obj->{'title'} = $value;
                unset($obj->{'DepartmentName'});
            } else if (is_object($value)) {
                self::convertKeys($value);
            } else if (is_array($value)) {
                foreach ($value as $val) {
                    self::convertKeys($val);
                }
            }
        }
    }
    public function recive_student_select()
    {

        // echo $list;exit;
        return View::fetch();
    }

    public function resoucesbook()
    {
        return View::fetch();
    }

    public static function deletefiles($path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }
    public function addbook()
    {

        $dimension1list = Db::name('ykt_lb')->where('dzbm', 'dimension1')->select();
        $dimension2list = Db::name('ykt_lb')->where('dzbm', 'dimension2')->select();

        $parentnodeidlist =   Db::name('ykt_ietm_content_tree')->select();

        View::assign([
            'dimension1list' => $dimension1list,
            'dimension2list' => $dimension2list,
            'parentnodeidlist' => $parentnodeidlist
        ]);

        return View::fetch();
    }
    public function bookedit()
    {
        $ietm_id = request()->get('ietm_id') ? request()->get('ietm_id') : 1;
        $selectdata = Db::name('ykt_ietm_resource_total')->where('ietm_id', $ietm_id)->find();
        $dimension1list = Db::name('ykt_lb')->where('dzbm', 'dimension1')->select();
        $dimension2list = Db::name('ykt_lb')->where('dzbm', 'dimension2')->select();
        $parentnodeidlist =   Db::name('ykt_ietm_content_tree')->select();
        $selectdata['CreateTime'] = date('Y-m-d', $selectdata['CreateTime']);

        $filelist = Db::name('ykt_ietm_resource_index')->where('ietm_id', $ietm_id)->select();
        $filedata = array("code" => 0, "data" => []);
        foreach ($filelist as $key => $value) {
            $select = Db::name('ykt_lb')->where('id', $value['ResourceType'])->find();
            $array = array(
                "id" => $key + 1,
                "sid" => $value['id'],
                "res" => 1, //1是上传过了，0等待上传
                "name" => $value['FileName'],
                "size" => $value['Size'],
                "type" => $select['lbmc'],
                "edit" => $value['edit'],
                "data"=>$value['data']==""?"无数据":json_decode($value["data"]),
                "FilePath" => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $value['FilePath']
            );
            array_push($filedata['data'], $array);
        }

        View::assign([
            'filedata' => json_encode($filedata),
            'dimension1list' => $dimension1list,
            'dimension2list' => $dimension2list,
            'parentnodeidlist' => $parentnodeidlist,
            'selectdata' => $selectdata,


        ]);
        return View::fetch();
    }



    public function deletebook()
    {
        $ietm_id = request()->get('ietm_id') ? request()->get('ietm_id') : 1;
        $select = Db::table('ykt_ietm_resource_total')->where('ietm_id', $ietm_id)->find();

        self::deletefiles($select["Thumbnail"]);

        Db::table('ykt_ietm_resource_total')->where('ietm_id', $ietm_id)->delete();

        $filelist = Db::table('ykt_ietm_resource_index')->where('ietm_id', $ietm_id)->select();

        foreach ($filelist as $key => $value) {
            self::deletefiles($value["FilePath"]);
            Db::table('ykt_ietm_resource_index')->where('ietm_id', $ietm_id)->delete();
            # code...
        }
        exit(json_encode(array('code' => 1, 'msg' => '删除成功')));
    }
    public function deleteResource()
    {
        $id = request()->get('id') ? request()->get('id') : 1;
        $select = Db::table('ykt_ietm_resource_index')->where('id', $id)->find();
        $file_path = $select['FilePath'];
        self::deletefiles($file_path);
        Db::table('ykt_ietm_resource_index')->where('id', $id)->delete();
        exit(json_encode(array('code' => 1, 'msg' => '删除成功')));
    }

    public function updatebookresource()
    {
        $ietm_id = request()->get('ietm_id') ? request()->get('ietm_id') : exit(1);


        if (Request::isPost()) {

            $data['authorid'] = input('authorid'); //下发人
            try {
                $selectdb = Db::table('ykt_user')->where('id', $data['authorid'])->find();
                // $insert = Db::table('ykt_ietm_resource_total')->insert($data, true);
            } catch (\Exception $e) {
                exit(json_encode(array('code' => 0, 'msg' => '检索错误')));
            }


            $user = $selectdb['username'];

            $data['parentnodeid'] = input('parentnodeid'); //教材目录节点
            $data['ietm_name'] = input('ietm_name'); //教程名称
            $data['Version'] = input('Version'); //教材版本
            $data['CreateTime'] = input('CreateTime'); //教材版本
            if (!empty($data['CreateTime'])) {
                $data['CreateTime'] = strtotime($data['CreateTime']);
            }
            $data['Dimension1'] = input('Dimension1'); //维度1
            $data['Dimension2'] = input('Dimension2'); //维度2
            $data['KeyWord'] = input('KeyWord'); //seo标签
            $data['isstudyplan'] = input('isstudyplan'); //是否下发学习计划
            $data['Introduction'] = input('Introduction'); //教材简介
            //接收人
            $data['Thumbnail'] = input('Thumbnail'); //缩略图
            $data['updateTime'] = substr(time(), 0, 10);


            // $filedata['Size'] = filesize($filedata['FilePath'])
            foreach ($data as $key => $value) {

                if ($value == "") {
                    exit(json_encode(array('code' => 0, 'msg' => '检索错误')));
                }
            }



            $data['reciveuser'] = input('reciveuser');
            try {
                Db::table('ykt_ietm_resource_total')->where('ietm_id', $ietm_id)->update($data);
                // $insert = Db::table('ykt_ietm_resource_total')->insert($data, true);
            } catch (\Exception $e) {
                // 输出异常信息
                echo 'Caught exception: ',  $e->getMessage(), "\n";
                exit;
            }


            $filedata['ietm_id'] =  $ietm_id;
            $jsondata = input('filelistdata');
            $uploadPath = 'storage/uploads/' . $user . '/document/' . date('Ymd') . '/'; // 根据用户名设置上传目录

            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0777, true)) { // 第三个参数表示递归创建所有不存在的上级目录
                    echo '无法创建目录';
                    exit;
                }
            }

            if (!empty($jsondata)) {
                foreach ($jsondata as $key => $value) {
                    if ($value['res'] == 0) {
                        $filename = basename($value['name'], '.' . pathinfo($value['name'], PATHINFO_EXTENSION));

                        $onlyid = uniqid();

                        $filepath = $uploadPath . $onlyid . '.' . $value['type'];

                        $resourcetype  = Db::name('ykt_lb')->where('lbmc', $value['type'])->select(); //获取类别id

                        $filedata['ResourceType'] = $resourcetype[0]['id']; //设置类别id
                        $filedata['FilePath'] = $filepath;
                        $filedata['FileName'] = $filename;
                        $filedata['Size'] = $value['size'];

                       

                        try {
                            if($value["edit"]==1){
                                $filedata['edit'] = 1;
                                $filedata['FilePath'] = $value["file"];
                                $filedata['data'] = json_encode($value["data"]);
                            }else{
                                $filedata['edit'] = 0;
                                $filedata['data'] = "";
                                $binarybody = substr(strstr($value['file'], ","), 1);
        
                                $binaryData = base64_decode($binarybody); //获取资源
                                validate(['file' => 'fileSize:209715200|fileExt:epub,wav,wma,flac,mp3,mp4,avi,mkv,doc,docx,pdf'])->check(array($value['type']));
        
                                file_put_contents($filepath, $binaryData);
                            }
                            try {
                                Db::table('ykt_ietm_resource_index')->insert($filedata);
                            } catch (\Exception $e) {
                                // 输出异常信息
                                echo 'Caught exception: ',  $e->getMessage(), "\n";
                                exit;
                            }
                        } catch (\Exception $e) {
                            exit(json_encode(array('code' => 0, 'msg' => '检索错误')));
                        }
                    }
                }
            }


            exit(json_encode(array('code' => 1, 'msg' => '更新成功')));
        }
    }
    public function addbookresource()
    {
        $user = Cookie::get("admin_name");
        if (Request::isPost()) {

            $data['authorid'] = input('authorid'); //下发人

            $data['parentnodeid'] = input('parentnodeid'); //教材目录节点
            $data['ietm_name'] = input('ietm_name'); //教程名称
            $data['Version'] = input('Version'); //教材版本
            $data['CreateTime'] = input('CreateTime'); //教材版本
            if (!empty($data['CreateTime'])) {
                $data['CreateTime'] = strtotime($data['CreateTime']);
            }
            $data['Dimension1'] = input('Dimension1'); //维度1
            $data['Dimension2'] = input('Dimension2'); //维度2
            $data['KeyWord'] = input('KeyWord'); //seo标签
            $data['isstudyplan'] = input('isstudyplan'); //是否下发学习计划
            $data['Introduction'] = input('Introduction'); //教材简介
            //接收人
            $data['Thumbnail'] = input('Thumbnail'); //缩略图
            $data['updateTime'] = substr(time(), 0, 10);


            // $filedata['Size'] = filesize($filedata['FilePath'])

            foreach ($data as $key => $value) {

                if ($value == "") {
                    exit(json_encode(array('code' => 0, 'msg' => '检索错误')));
                }
            }

            $data['reciveuser'] = input('reciveuser');




            try {
                $insert = Db::table('ykt_ietm_resource_total')->insert($data, true);
            } catch (\Exception $e) {
                // 输出异常信息
                echo 'Caught exception: ',  $e->getMessage(), "\n";
                exit;
            }
            if ($data['isstudyplan'] == 1) {
                if (!empty($data["reciveuser"])) {
                    $fruits = explode("|",  $data['reciveuser']);

                    foreach ($fruits as $key => $value) {
                        $insertdata["UserID"] = $value;
                        $insertdata["IETM_ID"] = $insert;
                        $insertdata["Is_Plan"] = 0;
                        $insertdata["ResourceState"] = 25;
                        Db::table("YKT_user_resource_sate")->insert($insertdata);
                    }
                }
            }

            $filedata['ietm_id'] =  $insert;
            $jsondata = input('filelistdata');
            $uploadPath = 'storage/uploads/' . $user . '/document/' . date('Ymd') . '/'; // 根据用户名设置上传目录

            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0777, true)) { // 第三个参数表示递归创建所有不存在的上级目录
                    echo '无法创建目录';
                    exit;
                }
            }

            if (!empty($jsondata)) {
                foreach ($jsondata as $key => $value) {
                    $filename = basename($value['name'], '.' . pathinfo($value['name'], PATHINFO_EXTENSION));

                    $onlyid = uniqid();

                    $filepath = $uploadPath . $onlyid . '.' . $value['type'];

                    $resourcetype  = Db::name('ykt_lb')->where('lbmc', $value['type'])->select(); //获取类别id

                    $filedata['ResourceType'] = $resourcetype[0]['id']; //设置类别id
                    $filedata['FilePath'] = $filepath;
                    $filedata['FileName'] = $filename;
                    $filedata['Size'] = $value['size'];
                    
                  
                    try {
                        if($value["edit"]==1){
                            $filedata['FilePath'] = $value["file"];
                            $filedata['data'] = json_encode($value["data"]);
                        }else{
                            $filedata['data'] = "";
                            $binarybody = substr(strstr($value['file'], ","), 1);
    
                            $binaryData = base64_decode($binarybody); //获取资源
                            validate(['file' => 'fileSize:209715200|fileExt:epub,wav,wma,flac,mp3,mp4,avi,mkv,doc,docx,pdf'])->check(array($value['type']));
    
                            file_put_contents($filepath, $binaryData);
                        }
                       
                        try {
                            Db::table('ykt_ietm_resource_index')->insert($filedata);
                        } catch (\Exception $e) {
                            // 输出异常信息
                            echo 'Caught exception: ',  $e->getMessage(), "\n";
                            exit;
                        }
                    } catch (\Exception $e) {
                        exit(json_encode(array('code' => 0, 'msg' => '检索错误')));
                    }
                }
            }


            exit(json_encode(array('code' => 1, 'msg' => '添加成功')));
        }
    }
    /**假接口 */
    public function falseupload()
    {
        exit(json_encode(array('code' => 0, 'msg' => '')));
    }

    /* 开启学习计划**/
    public function start_isplan()
    {
        if (Request::isPost()) {
            $ietm_id = input('ietm_id'); //维度1
            $res = Db::table('ykt_ietm_resource_total')->where('ietm_id', $ietm_id)->update([
                'isstudyplan' => Db::raw('NOT isstudyplan'),
            ]);

            if ($res) {
                $select = Db::table('ykt_ietm_resource_total')->where('ietm_id', $ietm_id)->find();
                if ($select['isstudyplan'] == 1) {
                    exit(json_encode(array('data' => 1, 'msg' => '学习计划已开启')));
                } else {
                    exit(json_encode(array('data' => 1, 'msg' => '学习计划已关闭')));
                }
            } else {
                exit(json_encode(array('data' => 0, 'msg' => '学习计划关闭失败')));
            }
        }
    }
    /*搜索教材资源*/
    public function search_resources()
    {
        $page = request()->get('page') ? request()->get('page') : 1;

        $limit = request()->get('limit') ? request()->get('limit') : 1;

        $ietm_name = request()->get('ietm_name') ? request()->get('ietm_name') : 1;

        $userid =  Cookie::get('admin_id');


        $l1 = $page * $limit - $limit;
        $l2 = $page * $limit;
        $count = Db::name('ykt_ietm_resource_total')->where([
            ['authorid', '=', $userid],
            ['ietm_name', 'like', '%' . $ietm_name . '%'],
        ])->count();

        $list = Db::name('ykt_ietm_resource_total')->where([
            ['authorid', '=', $userid],
            ['ietm_name', 'like', '%' . $ietm_name . '%'],
        ])->limit($l1, $l2)->select();



        $array = array(
            'code' => 0,
            'count' => $count,
            'data' => []
        );
        foreach ($list as $key => $value) {

            $jdb = Db::name('ykt_lb')->where('id', $value['Dimension1'])->find();
            $Dimension1 = $jdb['lbmc'];

            $jdb = Db::name('ykt_lb')->where('id', $value['Dimension2'])->find();
            $Dimension2 = $jdb['lbmc'];

            $jdb = Db::name('ykt_ietm_content_tree')->where('id', $value['parentnodeid'])->find();
            $parentnodeid = $jdb['NodeName'];

            array_push($array['data'], array(
                'index' => $key,
                'ietm_id' => $value['ietm_id'],
                'authorid' =>  Cookie::get('admin_name'),
                'Thumbnail' => $value['Thumbnail'],
                'ietm_name' => $value['ietm_name'],
                'Dimension1' => $Dimension1,
                'Dimension2' => $Dimension2,
                'parentnodeid' => $parentnodeid,
                'isstudyplan' => $value['isstudyplan'],
                'updateTime' => date('Y-m-d H:i:s', $value['updateTime']),
            ));
        }
        echo json_encode($array);
        return;
    }
    /*获取教材资源*/
    public function get_resources()
    {
        $page = request()->get('page') ? request()->get('page') : 1;

        $limit = request()->get('limit') ? request()->get('limit') : 1;


        $userid =  Cookie::get('admin_id');


        $l1 = $page * $limit - $limit;
        $l2 = $page * $limit;
        $count = Db::name('ykt_ietm_resource_total')->where('authorid', $userid)->count();

        $list = Db::name('ykt_ietm_resource_total')->where('authorid', $userid)->limit($l1, $l2)->select();



        $array = array(
            'code' => 0,
            'count' => $count,
            'data' => []
        );
        foreach ($list as $key => $value) {

            $jdb = Db::name('ykt_lb')->where('id', $value['Dimension1'])->find();
            $Dimension1 = $jdb['lbmc'];

            $jdb = Db::name('ykt_lb')->where('id', $value['Dimension2'])->find();
            $Dimension2 = $jdb['lbmc'];

            $jdb = Db::name('ykt_ietm_content_tree')->where('id', $value['parentnodeid'])->find();
            $parentnodeid = $jdb['NodeName'];

            array_push($array['data'], array(
                'index' => $key,
                'ietm_id' => $value['ietm_id'],
                'authorid' =>  Cookie::get('admin_name'),
                'Thumbnail' => $value['Thumbnail'],
                'ietm_name' => $value['ietm_name'],
                'Dimension1' => $Dimension1,
                'Dimension2' => $Dimension2,
                'parentnodeid' => $parentnodeid,
                'isstudyplan' => $value['isstudyplan'],
                'updateTime' => date('Y-m-d H:i:s', $value['updateTime']),
            ));
        }
        echo json_encode($array);
        return;
    }
    /**获取学生列表 */
    public function get_studenlist()
    {
        $list = Db::name('ykt_user')->where('role', 18)->select();
        $array = array(
            'code' => 0,
            'data' => []
        );
        foreach ($list as $key => $value) {
            array_push($array['data'], array(
                'index' => $key,
                'id' => $value['id'],
                'name' => $value['username'],
                'code' => 00,
                'moren' => "已设置<\/font>", //数据库未添加
                'ismoren' => 1,
            ));
        }
        echo json_encode($array);
        exit;
    }






    /** epub|wav|wma|flac|mp4|avi|mkv|doc|pdf上传接口*/
    // 上传接口

    public function upload()
    {

        $user =  Cookie::get("admin_name"); // 获取登录用户信息
        $uploadPath = 'uploads/' . $user . '/document'; // 根据用户名设置上传目录
        // 获取表单上传文件
        $file = request()->file();
        $files = request()->file('file');
        if ($file == null) {
            exit(json_encode(array('code' => 0, 'msg' => '没有文件上传')));
        }
        if (empty($user)) {
            exit(json_encode(array('code' => 0, 'msg' => '用户不存在')));
        }
        try {
            validate(['file' => 'fileSize:209715200|fileExt:epub,wav,wma,flac,mp4,avi,mkv,doc,pdf'])->check($file);
            $info = \think\facade\Filesystem::disk('public')->putFile($uploadPath, $files);
        } catch (\think\exception\ValidateException $e) {
            exit(json_encode(array('code' => 0, 'msg' => $e->getMessage())));
        }
        $info = str_replace("\\", "/", $info);
        $filepath = 'storage/' . $info;
        $fileattr = pathinfo($info, PATHINFO_EXTENSION);

        exit(json_encode(array('code' => 1, 'msg' => '上传成功', 'src' => $filepath, 'fileattr' => $fileattr)));
    }
    public function uploadImg()
    {

        $user =  Cookie::get("admin_name"); // 获取登录用户信息
        $uploadPath = 'uploads/' . $user . '/img'; // 根据用户名设置上传目录
        // 获取表单上传文件
        $file = request()->file();
        $files = request()->file('file');

        if ($file == null) {
            exit(json_encode(array('code' => 0, 'msg' => '没有文件上传')));
        }
        if (empty($user)) {
            exit(json_encode(array('code' => 0, 'msg' => '用户不存在')));
        }
        try {
            validate(['image' => 'fileSize:209715200|fileExt:jpg,png,gif,jpeg|bmp'])->check($file);
            $info = Filesystem::disk('public')->putFile($uploadPath, $files);
        } catch (\think\exception\ValidateException $e) {
            exit(json_encode(array('code' => 0, 'msg' => $e->getMessage())));
        }
        $fileattr = pathinfo($info, PATHINFO_EXTENSION);
        $info = str_replace("\\", "/", $info);
        $img = 'storage/' . $info;
        exit(json_encode(array('code' => 1, 'msg' => '上传成功', 'src' => $img, 'fileattr' => $fileattr)));
    }

    public function epubUploadImg()
    {

        $user =  Cookie::get("admin_name"); // 获取登录用户信息
        $uploadPath = 'uploads/' . $user . '/epubimg'; // 根据用户名设置上传目录
        // 获取表单上传文件
        $file = request()->file();
        $files = request()->file('wangeditor-uploaded-image');

        if ($file == null) {
            exit(json_encode(array('code' => 0, 'msg' => '没有文件上传')));
        }
        if (empty($user)) {
            exit(json_encode(array('code' => 0, 'msg' => '用户不存在')));
        }
        try {
            validate(['image' => 'fileSize:209715200|fileExt:jpg,png,gif,jpeg|bmp'])->check($file);
            $info = Filesystem::disk('public')->putFile($uploadPath, $files,'uniqid');
        } catch (\think\exception\ValidateException $e) {
            exit(json_encode(array('code' => 0, 'msg' => $e->getMessage())));
        }
        //$fileattr = pathinfo($info, PATHINFO_EXTENSION);
        $info = str_replace("\\", "/", $info);
        $img = '/storage/' . $info;
        exit(json_encode(array('errno' => 0, 'data' => array("url" => $img),)));
    }
    public function epubUploadVideo()
    {

        $user =  Cookie::get("admin_name"); // 获取登录用户信息
        $uploadPath = 'uploads/' . $user . '/epubVideo'; // 根据用户名设置上传目录
        // 获取表单上传文件
        $file = request()->file();
        $files = request()->file('wangeditor-uploaded-video');

        if ($file == null) {
            exit(json_encode(array('code' => 0, 'msg' => '没有文件上传')));
        }
        if (empty($user)) {
            exit(json_encode(array('code' => 0, 'msg' => '用户不存在')));
        }
        try {
            validate(['image' => 'fileSize:209715200|fileExt:mp4,mkv'])->check($file);
            $info = Filesystem::disk('public')->putFile($uploadPath, $files,'uniqid');
        } catch (\think\exception\ValidateException $e) {
            exit(json_encode(array('code' => 0, 'msg' => $e->getMessage())));
        }
        //$fileattr = pathinfo($info, PATHINFO_EXTENSION);
        $info = str_replace("\\", "/", $info);
        $img = '/storage/' . $info;
        exit(json_encode(array('errno' => 0, 'data' => array("url" => $img),)));
    }
}
