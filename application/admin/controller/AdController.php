<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
class AdController extends AdminController
{
    /**
     * [ad_list 轮播图管理]
     * @return [type] [description]
     */
    public function ad_list()
    {  
        return view();
    }

    //轮播图列表
    /**
     * [ad_list_ajax 轮播图列表]
     * @return [type] [description]
     */
    public function ad_list_ajax($id,$search ='')
    {
        
        return json(['total'=>$count,'rows'=>$data]);
       
    }
    //添加轮播图
    /**
     * [ad_add 添加轮播图]
     * @return [type] [description]
     */
    public function ad_add()
    {

        if($_POST or $_FILES){
            $data = input('post.');
           
            $rs = Db::table('th_ad')->insert($data);
            if($rs){
                $this->success('添加成功',url('ad_list'));
            }else{
                $this->error($rs['msg']);
            }
        }else{
           
            return view();
        }
            
    }
    //编辑轮播图
    /**
     * [ad_edit 编辑轮播图]
     * @return [type] [description]
     */
    public function ad_edit()
    {

        if($_POST){  
       
            $rs = Db::table('th_ad')->where('id',$data['id'])->update($data);
                
            if($rs){
                $this->success('修改成功',url('ad_list'));
            }else{
                $this->error($rs['msg']);
            }
                 

        }else{
            $id = input('id');

            $data = Db::table('th_ad')->where('id',$id)->find();
           
            $this -> assign('data',$data);
            
            return view();
        }
                
    }
    //删除轮播图
    /**
     * [ad_del 删除轮播图]
     * @param  string $id [轮播图id]
     * @return [type]     [description]
     */
    public function ad_del($id='')
    {
        $rst = Db::table('th_ad')->where('id',$id)->delete();
        if($rst){
            return json(['status'=>1]);
        }
        
    }
}
