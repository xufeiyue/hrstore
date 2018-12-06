<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class Treatment extends controller
{
    public function index()
    {
      $data_main1 = Db::name('main')->where(['id'=>'1'])->find();
      $data_main2 = Db::name('main')->where(['id'=>'2'])->find();
      $data_main3 = Db::name('main')->where(['id'=>'3'])->find();
      //dump($data1);die;
      $this->assign('data_main1',$data_main1);
      $this->assign('data_main2',$data_main2);
      $this->assign('data_main3',$data_main3);
      $a = nav();
      $this->assign('data1',$a['0']);
      return view();
    }
     public function consultation1()
    {
      $data_main1 = Db::name('main')->where(['id'=>'1'])->find();
      $this->assign('data_main1',$data_main1);
      $data = Db::name('main1')->where(['id'=>'1'])->find();
      $this->assign('data',$data);
     
        $a = nav();
      $this->assign('data1',$a['0']);
        return view('treatment/consultation1');
    }
     public function consultation2()
    {
      $data_main2 = Db::name('main')->where(['id'=>'2'])->find();
      $this->assign('data_main2',$data_main2);
      $data = Db::name('main1')->where(['id'=>'2'])->find();
      $this->assign('data',$data);
        $a = nav();
      $this->assign('data1',$a['0']);
         return view('treatment/consultation2');
    }
      public function consultation3()
    {

      $data_main3 = Db::name('main')->where(['id'=>'3'])->find();
      $this->assign('data_main3',$data_main3);
      $data = Db::name('main1')->where(['id'=>'3'])->find();
      $this->assign('data',$data);
        $a = nav();
      $this->assign('data1',$a['0']);
        return view('treatment/consultation3');
    }
}
