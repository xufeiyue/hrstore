<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class Join extends controller
{
    public function index()
    {
    	$data = Db::name('join')->find();
    	$data['duties'] = explode('|',$data['duties']);
    	$data['requirements'] = explode('|',$data['requirements']);
    	//dump($data);die();
    	$this->assign('data',$data);
        $a = nav();
    	$this->assign('data1',$a['0']);
        return view();
    }
    
}
