<?php
namespace app\admin\controller;
use http\Env;
use think\Controller;
use think\Request;

class UploadController extends Controller
{
	/*
		上传图片
	*/
	public function upload(Request $request){

		// 获取表单上传文件
        $file = $request->file('file');

        if (empty($file)) {
            return json(['code' => 0 , 'msg' => '请选择文件']);
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' .DS .'member');
        $name = $file->getInfo()['name'];
        $detail = $info->getInfo();

        $detail['size'] = round($detail['size']/1024,1).'kb';

        if ($info) {
            // $this->success('文件上传成功：' . $info->getRealPath());
            return json(['code'=> 0 ,'data'=> '/uploads/member/' . $info->getSaveName(), 'info' => urlencode(json_encode($detail)),'size'=>$detail['size'],'img_name'=>$name]);
        } else {
            // 上传失败获取错误信息
            // $this->error($file->getError());
            return json(['code'=> 1, 'data'=> '','msg'=> '上传失败']);
        }
	}

    /*
        上传富文本编辑器图片
    */
    public function editor_upload(Request $request){

        // 获取表单上传文件
        $file = $request->file('file');

        if (empty($file)) {
            return json(['code' => 0 , 'msg' => '请选择文件']);
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' .DS .'member');

        if ($info) {
            // $this->success('文件上传成功：' . $info->getRealPath());
            return json(['code'=> 0 ,'data'=> ['src' => $request->domain().'/uploads/member/' . $info->getSaveName() , 'title' => '']]);
        } else {
            // 上传失败获取错误信息
            // $this->error($file->getError());
            return json(['code'=> 1, 'data'=> '','msg'=> '上传失败']);
        }
    }

    public function excel_upload(Request $request){
        // 获取表单上传文件
        $file = $request->file('file');

        if (empty($file)) {
            return json(['code' => 0 , 'msg' => '请选择文件']);
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' .DS .'excels');

        $detail = $info->getInfo();

        $detail['size'] = round($detail['size']/1024,1).'kb';

        if ($info) {
            // $this->success('文件上传成功：' . $info->getRealPath());
            return json(['code'=> 0 ,'data'=> $_SERVER['DOCUMENT_ROOT'].'/uploads/excels/' . $info->getSaveName(), 'info' => urlencode(json_encode($detail))]);
        } else {
            // 上传失败获取错误信息
            // $this->error($file->getError());
            return json(['code'=> 1, 'data'=> '','msg'=> '上传失败']);
        }
    }
    //百度编辑器
    public function upload2() {

        date_default_timezone_set("Asia/Chongqing");
        error_reporting(E_ERROR);
        // header("Content-Type: text/html; charset=utf-8");

        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(ROOT_PATH . "config.json")), true);
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;

            /* 上传图片 */
            case 'uploadimage':
              $result = include(getcwd()."/static/admin/bianjiqi/php/action_upload.php");
                break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
                $result = include(getcwd() ."/public/static/admin/bianjiqi/php/action_upload.php");
                break;
            /* 上传视频 */
            case 'uploadvideo':
                $result = include(getcwd() ."/public/static/admin/bianjiqi/php/action_upload.php");
                break;
            /* 上传文件 */
            case 'uploadfile':
                $result = include(getcwd() ."/public/static/admin/bianjiqi/php/action_upload.php");
                break;

            /* 列出图片 */
            case 'listimage':
                $result = include(getcwd() . "/public/static/admin/bianjiqi/php/action_list.php");
                break;
            /* 列出文件 */
            case 'listfile':
                $result = include(getcwd() . "/public/static/admin/bianjiqi/php/action_list.php");
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = include(getcwd() ."/public/static/admin/bianjiqi/php/action_crawler.php");
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }
      
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
}