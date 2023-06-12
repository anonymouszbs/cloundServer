<?php

namespace app\ykt\controller;

use think\db\Where;
use think\facade\Filesystem;
use think\facade\Filesystem\File;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use think\facade\Cookie;
use Webapi;

use function PHPSTORM_META\type;

class Api extends Base
{


    /**实现日志列表获取 type=0 违规信息 1就是用户日志 */

    public function get_history_log_listdata()
    {
       

        $type = request()->get('type') ;
        $page = request()->get('page') ? request()->get('page') : 1;

        $limit = request()->get('limit') ? request()->get('limit') : 1;
        
        $l1 = $page * $limit - $limit;
        $l2 = $page * $limit;

        if($type==1){
            $count = Db::table('YKT_user_audit_history')->count();
            $select = Db::table('YKT_user_audit_history')->limit($l1, $l2)->select();
            $listdata = array();
            foreach ($select as $key => $value) {
                # code...
    
                $array = $value;
                if ($array["userid"] != 0) {
                    $user  = Db::table('ykt_user')->where('id', $value["userid"])->find();
                    $array["userid"] = $user["username"];
                }
                $array["actionTime"] = date("Y-h-d H:m:s", $array["actionTime"]);
                array_push($listdata, $array);
            }
            exit(json_encode(array('code' => 0, 'msg' => '获取成功','count'=>$count, 'data' => $listdata)));
        }else if($type==0){
            $count1 = Db::table('YKT_user_audit_illegal')->count();
            $select1 = Db::table('YKT_user_audit_illegal')->limit($l1, $l2)->select();
            $listdata1 = array();
            foreach ($select1 as $key => $value1) {
                # code...
    
                $array1 = $value1;
                if ($array1["userid"] != 0) {
                    $user1  = Db::table('ykt_user')->where('id', $value1["userid"])->find();
                    $array1["userid"] = $user1["username"];
                }
                $array1["actionTime"] = date("Y-h-d H:m:s", $array1["reportedTime"]);
                $array1["action"] = $array1["illegalType"];
                array_push($listdata1, $array1);
            }
            exit(json_encode(array('code' => 0, 'msg' => '获取成功','count'=>$count1, 'data' => $listdata1)));
        }
       
    }

    /**更新用户信息 */

    public function up_user_info()
    {
        $id = request()->get('id') ? request()->get('id') : 1;
        if (Request::isPost()) {
            $data = input();
            $data['pwd'] = $data['pwd'] == "" ? "" : md5($data['pwd']);
            if ($data['pwd'] == "") {
                unset($data['pwd']);
            }

            $data['updatetime'] = substr(time(), 0, 10);
            foreach ($data as $key => $value) {
                if ($value == "") {
                    exit(json_encode(array('code' => 0, 'msg' => '检索错误')));
                }
            }

            $insert = Db::table('ykt_user')->where('id', $id)->update($data);
            exit(json_encode(array('code' => 1, 'msg' => '更新成功')));
        }
    }
    /**添加用户*/
    public function insertUser()
    {
        if (Request::isPost()) {
            $data = input();
            $data['pwd'] = md5($data['pwd']);
            $data['create_time'] = substr(time(), 0, 10);
            $data['updatetime'] = substr(time(), 0, 10);
            foreach ($data as $key => $value) {

                if ($value == "") {
                    exit(json_encode(array('code' => 0, 'msg' => '检索错误')));
                }
            }
            $select =  Db::table('ykt_user')->where('username', $data['username'])->count();
            if ($select > 0) {

                exit(json_encode(array('code' => 0, 'msg' => '用户名已存在')));
            }


            $insert = Db::table('ykt_user')->insert($data, true);
            exit(json_encode(array('code' => 1, 'msg' => '添加成功')));
        }
    }

    /**搜索用户列表 */
    public function search_user_list()
    {
        $page = request()->get('page') ? request()->get('page') : 1;

        $limit = request()->get('limit') ? request()->get('limit') : 1;

        $username = request()->get('username') ? request()->get('username') : "";
        $Department = request()->get('Department') ? request()->get('Department') : "";
        $role = request()->get('role') ? request()->get('role') : "";

        $l1 = $page * $limit - $limit;
        $l2 = $page * $limit;

        $userid =  Cookie::get('admin_id');
        $where = array();
        if ($username != "") {
            array_push($where, ['username', 'like', '%' . $username . '%']);
        } else if ($Department != "") {
            array_push($where, ['Department', '=', $Department]);
        } else if ($role != "") {
            array_push($where, ['role', '=', $role]);
        }



        $count = Db::table('ykt_user')->where('id', '<>', $userid)->where(function ($query) use ($where) {
            $query->whereOr($where);
        })->count();

        $list = Db::table('ykt_user')->where('id', '<>', $userid)->where(function ($query) use ($where) {
            $query->whereOr($where);
        })->limit($l1, $l2)->select();

        $array = array(
            'code' => 0,
            'count' => $count,
            'data' => []
        );
        foreach ($list as $key => $value) {
            $data = $value;
            $jdb = Db::table("YKT_department")->where('id',  $value['Department'])->find();
            $data['Department'] = $jdb['DepartmentName']; //部门
            $jdb = Db::table('ykt_lb')->where('id', $value['role'])->find();
            $data['role'] = $jdb['lbmc'];
            $data['index'] = $key;
            $data['create_time'] = date("Y-m-d H:m:s", $data['create_time']);
            array_push($array['data'], $data);
        }
        echo json_encode($array);
        return;
    }
    /**获取用户列表 */
    public function get_user_list()
    {
        $page = request()->get('page') ? request()->get('page') : 1;

        $limit = request()->get('limit') ? request()->get('limit') : 1;

        $userid =  Cookie::get('admin_id');

        $l1 = $page * $limit - $limit;
        $l2 = $page * $limit;
        $count = Db::name('ykt_user')->where('id', '<>', $userid)->count();
        $list = Db::name('ykt_user')->where('id', '<>', $userid)->limit($l1, $l2)->select();

        $array = array(
            'code' => 0,
            'count' => $count,
            'data'=>[]
        );
        
        foreach ($list as $key => $value) {
            $data = $value;
            $jdb = Db::table("YKT_department")->where('id',  $value['Department'])->find();
            $data['Department'] = $jdb['DepartmentName']; //部门
            $jdb = Db::table('ykt_lb')->where('id', $value['role'])->find();
            $data['role'] = $jdb['lbmc'];
            $data['index'] = $key;
            $data['create_time'] = date("Y-m-d H:m:s", $data['create_time']);
            
            array_push($array['data'], $data);
        }
        echo json_encode($array);
        exit;
        
    }
    /**删除用户 */
    public function del_user()
    {
        $id = request()->get('id') ? request()->get('id') : 1;

        Db::table('ykt_user')->where('id', $id)->delete();


        exit(json_encode(array('code' => 1, 'msg' => '删除成功')));
    }
    /**授权用户 */
    public function isAllowedLogin()
    {
        if (Request::isPost()) {
            $id = input('id'); //维度1
            $res = Db::table('ykt_user')->where('id', $id)->update([
                'AllowedLogin' => Db::raw('NOT AllowedLogin'),
            ]);

            if ($res) {
                $select = Db::table('ykt_user')->where('id', $id)->find();
                if ($select['AllowedLogin'] == 1) {
                    exit(json_encode(array('data' => 1, 'msg' => '已授权')));
                } else {
                    exit(json_encode(array('data' => 1, 'msg' => '已关闭授权')));
                }
            } else {
                exit(json_encode(array('data' => 0, 'msg' => '授权失败')));
            }
        }
    }

    public function saveEpub()
    {

        $user = Cookie::get("admin_name");
        $workpath = "storage/uploads/$user/epubwork/"; //epub的工作目录 需要把一些内容放进去 并压缩成1.epub
        //循环先放图片
        $imagelist = [];
        $chaptermanifest = "";
        $imagemanifest = "";
        $videomanifest = "";
        $spine = "";
        $toc = "";
        if (Request::isPost()) {
            $data = input("data");
            foreach ($data as $key => $value) {
                $filename =  $workpath . "text/chapter" . $value["index"] . ".html";
                $filecode = $value["value"];
                if (!empty($value["value"])) {
                    $filecode = str_replace("/storage/uploads/admin/epubVideo/", "../videos/", $filecode);
                    $filecode = str_replace("/storage/uploads/admin/epubimg/", "../images/", $filecode);
                } else {
                    $filecode = "章节无内容";
                }
                $chaptermanifest = $chaptermanifest . '<item href="' . "text/chapter" . $value["index"] . ".html" . '" id="' . $value["index"] . '" media-type="application/xhtml+xml"/>
                ';
                $spine = $spine . '<itemref idref="' . $value["index"] . '"/>
                ';

                $toc = $toc . '<navPoint class="chapter" id="num_' . $value["index"] . '" playOrder="' . $value["index"] . '">
                <navLabel>
                  <text>' . $value["title"] . '</text>
                </navLabel>
                <content src="' . "text/chapter" . $value["index"] . ".html" . '"/>
              </navPoint>
              ';
                file_put_contents($filename, $filecode);
                if (!empty($value["images"])) {
                    foreach ($value["images"] as $key => $image) {
                        $href =  str_replace("/storage/uploads/admin/epubimg/", "images/", $image);
                        $imagemanifest  = $imagemanifest . '<item href="' . $href . '" id="id11' . $key . '" media-type="image/jpeg"/>
                        
                        ';
                        self::file2dir(substr($image, 1), $workpath . "images/");
                    }
                }
                if (!empty($value["videos"])) {
                    foreach ($value["videos"] as $key => $video) {
                        $href =  str_replace("/storage/uploads/admin/epubVideo/", "videos/", $video);
                        $videomanifest  = $videomanifest . '<item href="' . $href . '" id="id21' . $key . '" media-type="video/mp4"/>
                        ';
                        self::file2dir(substr($video, 1), $workpath . "videos/");
                    }
                }
            }
            // echo $chaptermanifest;
            // echo $spine;
            // echo $imagemanifest;
            // echo $videomanifest;
            // echo $toc;

            $strtoc = '<?xml version=\'1.0\' encoding=\'utf-8\'?>
<ncx xmlns="http://www.daisy.org/z3986/2005/ncx/" version="2005-1" xml:lang="zho">
    <head>
    <meta content="3e12940b-581a-4b04-b871-f9e42a33a724" name="dtb:uid"/>
    <meta content="3" name="dtb:depth"/>
    <meta content="calibre (4.5.0)" name="dtb:generator"/>
    <meta content="0" name="dtb:totalPageCount"/>
    <meta content="0" name="dtb:maxPageNumber"/>
    </head>
    <docTitle>
    <text>' . input("bookname") . '</text>
    </docTitle>
    <navMap>
    ' . $toc . '
    </navMap>
    </ncx>';


            $contentopf = '<?xml version="1.0"  encoding="UTF-8"?>
          <package xmlns="http://www.idpf.org/2007/opf" unique-identifier="uuid_id" version="2.0">
            <metadata xmlns:calibre="http://calibre.kovidgoyal.net/2009/metadata" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:opf="http://www.idpf.org/2007/opf" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
              <dc:title>' . input("bookname") . '</dc:title>
              <dc:creator opf:role="aut" opf:file-as="未知">未知</dc:creator>
              <dc:contributor opf:role="bkp">calibre (4.5.0) [https://calibre-ebook.com]</dc:contributor>
              <dc:identifier id="uuid_id" opf:scheme="uuid">3e12940b-581a-4b04-b871-f9e42a33a724</dc:identifier>
              <dc:publisher>中信出版集团</dc:publisher>
              <dc:date>2020-05-31T16:00:00+00:00</dc:date>
              <dc:language>zh</dc:language>
              <dc:identifier opf:scheme="calibre">3e12940b-581a-4b04-b871-f9e42a33a724</dc:identifier>
              <dc:identifier opf:scheme="ISBN">9787521716658</dc:identifier>
              <dc:identifier opf:scheme="MOBI-ASIN">8378b2d1-6e94-4605-a0b1-0280a5d1d495</dc:identifier>
              <meta name="cover" content="cover"/>
              <meta name="calibre:timestamp" content="2020-09-04T05:01:07.371000+00:00"/>
              <meta name="calibre:title_sort" content="' . input("bookname") . '"/>
              <meta content="horizontal-lr" name="primary-writing-mode"/>
            </metadata>
            <manifest>
                ' . $chaptermanifest . '
              <item href="toc.ncx" id="ncx" media-type="application/x-dtbncx+xml"/>
              <item href="page_styles.css" id="page_css" media-type="text/css"/>
              <item href="stylesheet.css" id="css" media-type="text/css"/>
              ' . $imagemanifest . $videomanifest . '
            </manifest>
            <spine toc="ncx">
                ' . $spine . '
            </spine>
            <guide>
              
            </guide>
          </package>
          ';
            $dom = new \DOMDocument;
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($contentopf);
            $formattedXml = $dom->saveXML();

            file_put_contents($workpath . "content.opf", $formattedXml);
            $dom->loadXML($strtoc);
            $formattedXml = $dom->saveXML();
            file_put_contents($workpath . "toc.ncx", $formattedXml);

            $zip = new \ZipArchive;
            $zipname = uniqid() . '.epub';

            if ($zip->open($zipname, \ZipArchive::CREATE) === TRUE) {
                $dir = $workpath;
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($dir),
                    \RecursiveIteratorIterator::SELF_FIRST
                );

                foreach ($iterator as $fileinfo) {
                    if ($fileinfo->isFile()) {
                        // 去除$dir前缀的相对路径
                        $filePath = substr_replace($fileinfo->getPathname(), '', 0, strlen($dir));
                        $zip->addFile($fileinfo->getPathname(), $filePath);
                    } else if ($fileinfo->isDir() && !in_array(basename($fileinfo->getPathname()), array('.', '..'))) {
                        // 去除$dir前缀的相对路径
                        $filePath = substr_replace($fileinfo->getPathname(), '', 0, strlen($dir));
                        $zip->addEmptyDir($filePath);
                    }
                }

                $zip->close();
            }

            $newPath = "storage/uploads/$user/epub/" . $zipname; // 设置要移动到的新路径
            if (!is_dir("storage/uploads/$user/epub/")) {
                mkdir("storage/uploads/$user/epub/");
            }
            rename($zipname, $newPath);
            $size = filesize($newPath);
            echo json_encode(array("code" => 1, "size" => $size, "file" => $newPath, "name" => input("bookname"), "type" => "epub"));
            exit;
        }
    }

    /**更新epub内容 */
    public function upEpubInfo()
    {
        $id = request()->get('id');
        if (Request::isPost()) {
            $data = input();
            $data["data"] = json_encode($data["data"]);
            Db::table('ykt_ietm_resource_index')->where('id', $id)->update($data);
            echo json_encode(array("code" => 1));
            exit;
        }
    }
    /**获取教材分类 */

    public function ykt_content_tree()
    {
        $getdata = request()->get();
       
        $select = Db::table("ykt_ietm_content_tree")->select();
        $array = array();
        foreach ($select as $key => $value) {
            # code...
            $categories = $value;
            $categories["ParentID"] = $value["ParentID"]==0?-1:$value["ParentID"];
            array_push($array,$categories);
        }

        // $tree =self::buildTree(0,$categories);
        echo json_encode(array('code' => 1, 'msg' => "请求成功", "data" => $array));
    }
    /**添加教材分类 */
    public function add_content_tree()
    {
        $data= request()->post();
        $data["create_time"] = substr(time(),0,10);
        $select = Db::table("ykt_ietm_content_tree")->insert($data);
        // $tree =self::buildTree(0,$categories);
        echo json_encode(array('code' => 1, 'msg' => "添加成功"));
    }
    /**更新教材分类 */
    public function up_content_tree()
    {
        $id = request()->get("id");
        $data= request()->post();
        $select = Db::table("ykt_ietm_content_tree")->where('id',$id)->insert($data);
        // $tree =self::buildTree(0,$categories);
        echo json_encode(array('code' => 1, 'msg' => "更新成功"));
    }
    /**删除教材 */
    public function del_content_tree()
    {
        $id = request()->get("id");
        $select = Db::table("ykt_ietm_content_tree")->where('id',$id)->delete();
        // $tree =self::buildTree(0,$categories);
        echo json_encode(array('code' => 1, 'msg' => "删除成功"));
    }
    function file2dir($sourcefile, $dir)
    {
        $source = $sourcefile;
        $dest = $dir;

        // 如果目标路径是一个目录，则以源文件名作为新文件名
        if (is_dir($dest)) {
            $dest .= basename($source);
        }

        // 复制文件
        if (copy($source, $dest)) {
        } else {
        }
    }
}
