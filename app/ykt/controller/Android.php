<?php

namespace app\ykt\controller;


use think\facade\Db;
use think\facade\Request;

class Android
{
    //注册
    public function regit()
    {
        if (Request::isPost()) {
            $data = request()->post();
            foreach ($data as $key => $value) {
                if ($value == "") {
                    exit(json_encode(array('code' => 0, 'msg' => json_encode($data))));
                }
            }
            $data['pwd'] = md5($data['pwd']);
            $data['create_time'] = substr(time(), 0, 10);
            $data['updatetime'] = substr(time(), 0, 10);
            $data['LoginState'] = 0;
            $data['AllowedLogin'] = 1;
            $data['role'] = 18; //学生
            
            $select =  Db::table('ykt_user')->where('username', $data['username'])->count();
            if ($select > 0) {
                exit(json_encode(array('code' => 0, 'msg' => '用户名已存在')));
            }


            $insert = Db::table('ykt_user')->insert($data, true);
            $getdata = request()->get();
            self::addHistory($getdata, "有新用户注册了：用户名" . $data['username']);
            exit(json_encode(array('code' => 1, 'msg' => '注册成功')));
        }
    }
    //登录
    public function login()
    {
        if (Request::isPost()) {
            $data = request()->post();

            $find = Db::table('ykt_user')->where([
                ['username', '=', $data['username']],
                ['pwd', '=', md5($data['pwd'])]
            ])->find();


            $select = Db::table('YKT_department')->where([
                ['id', '=', $find['Department']],

            ])->find();

            $find['Department'] =  $select['DepartmentName'];
            if ($find > 0) {
                $getdata = request()->get();
                self::addHistory($getdata, "用户请求登录：" . $data['username']);
                echo json_encode(array('code' => 1, 'msg' => "登录成功", 'data' => $find));
                exit;
            } else {
                exit(json_encode(array('code' => 0, 'msg' => "登录失败，请检查账号或者密码是否填写错误")));
            }
        }
    }


    public function timediff($end_time)
    {
        $begin_time = substr(time(), 0, 10);
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }


        //计算天数
        $timediff = $endtime - $starttime;

        $days = intval($timediff / 86400);
        //计算小时数
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600);
        //计算分钟数
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        //计算秒数
        $secs = $remain % 60;

        if ($days > 0) {
            return "剩余$days" . "天";
        } else if ($remain >= 1) {
            return "剩余$remain" . "小时";
        } else if ($mins) {
            return "剩余$mins" . "分";
        } else if ($secs) {
            return "剩余$secs" . "秒";
        }
    }
    //下载
    public function download()

    {

        if (Request::isPost()) {
            $ietm_id = input("ietm_id");
            $select = Db::table("ykt_ietm_resource_index")->where("ietm_id", $ietm_id)->select();
            $ietm_name = Db::table("ykt_ietm_resource_total")->where("ietm_id", $ietm_id)->find();


            $parentnodeid = Db::table("ykt_ietm_content_tree")->where("id", $ietm_name["parentnodeid"])->find(); //教材类型
            $authorid = Db::table("ykt_user")->where("id", $ietm_name["authorid"])->find();
            $CreateTime = date("Y-h-d", $ietm_name["CreateTime"]);
            if (empty($authorid["user_nick"])) {
                $authorid["user_nick"] = "昵称为空";
            }

            foreach ($select as $key => $value) {
                # code...
                $filetype = Db::table("ykt_lb")->where("id", $value["ResourceType"])->find();
                $value["ResourceType"] =  $filetype["lbmc"];
                $selectlist[$key] = $value;
                $selectlist[$key]["LearningRate"] = 0;
            }

            echo json_encode(array("code" => 1, "msg" => "数据获取成功", "Introduction" => $ietm_name["Introduction"], "CreateTime" => $CreateTime, "user_nick" => $authorid["user_nick"], "parentnodeid" => $parentnodeid["NodeName"], "ietm_name" => $ietm_name["ietm_name"], "data" => $selectlist));
            $getdata = request()->get();
            self::addHistory($getdata, "用户下载资源：" . $ietm_name["ietm_name"]);
            exit;
        }
    }

    //获取学习计划表
    public function get_book_plan_shelf()
    {
        $getdata = request()->get();
        self::addHistory($getdata, "用户请求计划表：");
        $listdata = array();
        if (Request::isPost()) {
            $data = request()->post();
            $select = Db::table('YKT_user_resource_sate')->where([
                ['UserID', "=", $data["UserID"]],
                ['ResourceState', "=", $data["ResourceState"]]
            ])->select();


            foreach ($select as $key => $value) {
                $IETM_ID = $value["IETM_ID"];

                $j_select = Db::table('ykt_ietm_resource_total')->where([
                    ['ietm_id ', "=", $IETM_ID],
                ])->find();

                $Dimension1name = Db::table('ykt_lb')->where('id', $j_select["Dimension1"])->find();

                $Dimension2name = Db::table('ykt_lb')->where([
                    ['id', "=", $j_select['Dimension2']],
                ])->find();
                $authoridname  = Db::table('ykt_user')->where([
                    ['id', "=", $j_select['authorid']],
                ])->find();
                //截止时间

                $remainder = self::timediff($j_select['CreateTime']);
                $learners = Db::table('YKT_user_resource_sate')->where('IETM_ID', $IETM_ID)->count();
                $array = array(
                    "ietm_id" => $j_select["ietm_id"],
                    "ietm_name" => $j_select["ietm_name"],
                    "Thumbnail" => $j_select["Thumbnail"],
                    "learners" => $learners,
                    "Dimension1name" => $Dimension1name["lbmc"],
                    "Dimension2name" => $Dimension2name["lbmc"],
                    "authoridname" => $authoridname["user_nick"],
                    "remainder" => $remainder,
                    "endtime" => date('Y-m-d', $j_select['CreateTime'],),
                    "download" => false,
                    "covertitle" => "电子教材",
                );
                array_push($listdata, $array);
            }
            echo json_encode(array("code" => 1, "msg" => "数据获取成功", "data" => $listdata));
            exit;
        }
    }
    //addbookshelf 加入书架
    public function add_book_shelf()
    {
        $getdata = request()->get();
        self::addHistory($getdata, "用户添加书籍到书架");
        if (Request::isPost()) {
            $data = request()->post();
            $data["Is_Plan"] = true;
            $data["ResourceState"] = 24;
            $select = Db::table('YKT_user_resource_sate')->where([
                ['UserID', "=", $data["UserID"]],
                ['IETM_ID', "=", $data["IETM_ID"]]
            ])->find();
            if ($select < 1) {
                Db::table('YKT_user_resource_sate')->insert($data);



                echo json_encode(array('code' => 1, 'msg' => "添加学习计划成功"));
                exit;
            } else {
                echo json_encode(array('code' => 1, 'msg' => "已经添加过本计划"));
                exit;
            }
        }
    }

    public function getip()
    {
        $ip = '';
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_CDN_SRC_IP'])) {
            $ip = $_SERVER['HTTP_CDN_SRC_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] as $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        }
        return $ip;
    }
    function addHistory($data, $action)
    {
        //  echo json_encode($data);exit;

        $dataj["DiveceID"] = $data["DiveceID"]; //
        $dataj["DeveceName"] = $data["DeveceName"];
        $dataj["TerminalIP"] = self::getip();
        $dataj["userid"] = $data["userid"];
        $dataj["action"] = $action;
        $dataj["actionTime"] = substr(time(), 0, 10);

        Db::table('YKT_user_audit_history')->insert($dataj);
    }
    //违规记录接口
    public function ykt_user_audit_illegal(){
        $getdata = request()->get();
        if (Request::isPost()) {
            $data = request()->post();
            $data["userid"] = $getdata["userid"] ;
            $data["DiveceID"] = $getdata["DiveceID"];
            $data["TerminalIP"] = self::getip();
            $data["illegalStartTime"] = substr(time(),0,10);
            $data["reportedTime"] = substr(time(),0,10);
            unset($data["DeveceName"]);
            Db::table('YKT_user_audit_illegal')->insert($data);
            exit;
        }
    }
    //获取资源接口
    public function find_ietm_resource_total()
    {
        //留空 判断分页 yktlb 顶级分类 二级请求 
        //查询分类列表


        if (Request::isPost()) {
            $wheresql = [];
            $UserID = input("UserID");
            $Dimension1 = input("Dimension1");
            if (!empty($Dimension1)) {
                array_push($wheresql, ['Dimension1', '=', input("Dimension1")]);
            }
            $Dimension2 = input("Dimension2");
            if (!empty($Dimension2)) {
                array_push($wheresql, ['Dimension2', '=', input("Dimension2")]);
            }
            $parentnodeid =  input("parentnodeid");
            if (!empty($parentnodeid)) {
                array_push($wheresql, ['parentnodeid', '=', input("parentnodeid")]);
            }
            $ietm_name =  input("ietm_name");

            $getdata = request()->get();
            self::addHistory($getdata, "用户请求搜索关键字：$ietm_name");
            if (!empty($ietm_name)) {
                array_push($wheresql, ['ietm_name', 'like', "%" . input("ietm_name") . "%"]);
            }




            $select = Db::table('ykt_ietm_resource_total')->order('ietm_id', 'desc')->where($wheresql)->select();
            $booklist = array();
            foreach ($select as $key => $value) {
                //学习人数查询
                $j_select = Db::table('YKT_user_resource_sate')->where('IETM_ID', $value["ietm_id"])->count();
                $Dimension1name = Db::table('ykt_lb')->where('id', $value["Dimension1"])->find();
                $Dimension2name = Db::table('ykt_lb')->where('id', $value["Dimension2"])->find();
                $authoridname = Db::table('ykt_user')->where('id', $value["authorid"])->find();

                $array = array(
                    "ietm_id" => $value["ietm_id"], //书id
                    "ietm_name" => $value["ietm_name"], //书名称
                    "Thumbnail" => $value["Thumbnail"], //书缩略图
                    "learners" => $key == 5 ? 1 : $j_select, //
                    "Dimension1name" => $Dimension1name["lbmc"],
                    "Dimension2name" => $Dimension2name["lbmc"],
                    "authoridname" => $authoridname["user_nick"],

                );

                if (!empty($UserID)) {
                    $Is_Plan = Db::table('YKT_user_resource_sate')->where([
                        ["UserID", '=', $UserID],
                        ["IETM_ID", '=', $value["ietm_id"]]
                    ])->find();

                    if ($Is_Plan > 1) {
                        $array["Is_Plan"] = $Is_Plan["Is_Plan"];
                    } else {
                        $array["Is_Plan"] = 0;
                    }
                }

                array_push($booklist, $array);
            }

            //  usort($booklist, function ($a, $b) {
            //     if($a['learners'] == $b['learners']) return 0;
            //     return ($a['learners'] < $b['learners'])?1:-1;
            // });

            echo json_encode(array('code' => 1, 'msg' => "请求成功", "data" => $booklist));
        }
    }

    ///无限极教材分类目录
    public function ykt_content_tree()
    {
        $getdata = request()->get();
        
        $categories = Db::table("ykt_ietm_content_tree")->select();

        // $tree =self::buildTree(0,$categories);
        echo json_encode(array('code' => 1, 'msg' => "请求成功", "data" => $categories));
    }


    static function buildTree($parentID, &$categories)
    {
        $tree = array();
        foreach ($categories as $category) {
            if ($category['ParentID'] == $parentID) {
                $children = self::buildTree($category['id'], $categories);
                if ($children) {
                    $category['children'] = $children;
                }
                $tree[] = $category;
            }
        }
        return $tree;
    }
    //二级分类接口
    public function ykt_lb_secondarylevel()
    {
        $toplevel = array();
        $select = Db::table('ykt_lb')->order('bz', 'asc')->where('dzbm', 'dimension2')->limit(0, 3)->select();
        foreach ($select as $value) {

            array_push($toplevel, array("id" => $value['id'], "lbmc" => $value['lbmc']));
        }
        echo json_encode(array('code' => 1, 'msg' => "请求成功", "data" => $toplevel));
        exit;
    }
    //顶级分类接口
    public function ykt_lb_toplevel()
    {
        $toplevel = array();
        $select = Db::table('ykt_lb')->order('bz', 'asc')->where('dzbm', 'dimension1')->limit(0, 3)->select();
        foreach ($select as $value) {
            array_push($toplevel, array("id" => $value['id'], "lbmc" => $value['lbmc']));
        }
        echo json_encode(array('code' => 1, 'msg' => "请求成功", "data" => $toplevel));
        exit;
    }
    //部门分类接口
    public function ykt_department()
    {
        $find = Db::table('YKT_department')->select();

        if (count($find) > 0) {
            echo json_encode(array('code' => 1, 'msg' => "获取成功", 'data' => $find));
            exit;
        } else {
            echo json_encode(array('code' => 0, 'msg' => "没有部门分类"));
            exit;
        }
    }
}
