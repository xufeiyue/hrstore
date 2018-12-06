<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class News extends controller
{
    public function index()
    {
    	$data = Db::name('news')->order('time desc')->select();
    	
         foreach ($data as $k => $v) {
           $data[$k]['time'] = date("Y/m/d", $v['time']) ; 
           $data[$k]['time']= explode('/', $data[$k]['time']);
        }//dump($data);die();
    	$this->assign('data',$data);
        $a = nav();
    	$this->assign('data1',$a['0']);
        return view();
    }
    public function content($id='')
    {
        //$id = $_GET['id'];
        $data = Db::name('news')->order('time desc')->where(['id'=>$id])->find();
        $data['time'] =date('Y-m-d',$data['time']);
       // dump($data);die();
        $this->assign('data',$data);
        $a = nav();
        $this->assign('data1',$a['0']);
        return view();
    }
    
}
