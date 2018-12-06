<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class Training extends controller
{
    public function index()
    {
        $data = Db::name('editors_font')->where(['type_fixed'=>'医疗培训'])->order('id asc')->select();$this->assign('data',$data);//dump($data);die();
        
        $a = nav();
        $this->assign('data1',$a['0']);
        return view();
    }
     public function training1()
    {
        $data = Db::name('editors_font')->where(['type_fixed'=>'全科医生'])->order('id asc')->select();$this->assign('data',$data);
        $data_teacher = Db::name('teacher')->where(['status'=>'1'] )->order('id asc')->select();$this->assign('data_teacher',$data_teacher);
       
        $a = nav();$this->assign('data1',$a['0']);

        return view('training/training1');
    }
     public function training2()
    {
        $data = Db::name('editors_font')->where(['type_fixed'=>'医院管理'])->order('id asc')->select();$this->assign('data',$data);
        $data_left = Db::name('middle')->where(['location'=>'left'])->find();
       
        $data_left['body'] = explode(',',$data_left['body']);$this->assign('data_left',$data_left); 
        $data_right = Db::name('middle')->where(['location'=>'right'])->find();
       
        $data_right['body'] = explode(',',$data_right['body']);$this->assign('data_right',$data_right);
        $a = nav();
        $this->assign('data1',$a['0']);
         return view('training/training2');
    }
   
}
