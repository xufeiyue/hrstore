<?php
namespace app\home\controller;
use think\Controller;

class Science extends controller
{
    public function index()
    {
        $a = nav();
    	$this->assign('data1',$a['0']);
        return view();
    }
}
