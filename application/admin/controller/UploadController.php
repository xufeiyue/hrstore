<?php
namespace app\admin\controller;
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

        if ($info) {
            // $this->success('文件上传成功：' . $info->getRealPath());
            return json(['code'=> 0 ,'data'=> '/uploads/member/' . $info->getSaveName()]);
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
            return json(['code'=> 0 ,'data'=> ['src' => 'http://www.shop.com/uploads/member/' . $info->getSaveName() , 'title' => '']]);
        } else {
            // 上传失败获取错误信息
            // $this->error($file->getError());
            return json(['code'=> 1, 'data'=> '','msg'=> '上传失败']);
        }
    }
}