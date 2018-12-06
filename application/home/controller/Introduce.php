<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class Introduce extends controller
{
    public function index()
    {
    	$banner1 = Db::name('editors_pic')->where(['type_fixed'=>'合作机构1'])->find();
    	$this->assign('banner1',$banner1);
    	$banner2 = Db::name('editors_pic')->where(['type_fixed'=>'合作机构2'])->find();
    	$this->assign('banner2',$banner2);
    	$a = nav();
    	$this->assign('data1',$a['0']);
        return view();
       
    }
}
