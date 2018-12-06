<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class Usadoctors extends controller
{
    public function index()
    {
    	$list = Db::name('doctor')->where(['status'=>'1'])->select();
        
         //dump($list);die();
    	$this->assign('list',$list);
    	
        $a = nav();
    	$this->assign('data1',$a['0']);
        return view();
    }
}
