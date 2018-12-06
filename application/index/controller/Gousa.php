<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class Gousa extends controller
{
    public function index()
    {
         $data = Db::name('editors_font')->where(['type_fixed'=>'赴美医疗'])->order('number asc')->select();
         $data['10']['body'] = explode(',',$data['10']['body']);
         
         $banner = Db::name('editors_pic')->where(['type_fixed'=>'赴美医疗'])->find();
         $this->assign('banner',$banner);
    	$this->assign('data',$data);
        $a = nav();
        $this->assign('data1',$a['0']);
        return view();
        
    }
      public function index1()
    {
        
        return view();
        
    }
}
