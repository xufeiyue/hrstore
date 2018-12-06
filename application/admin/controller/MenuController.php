<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 朱亚杰 <zhuyajie@topthink.net>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use admin\model\AuthRule;
use admin\model\AuthGroup;
use think\Db;
/**
 * 菜单管理控制器
 * @author kjcx
 */
class MenuController extends AdminController{
    /**
     * 后台菜单首页
     * @return none
     */
    public function index($value='')
    {   
        return view();
    }

    //菜单列表
    public function menu_list(){
        return view();
    }

    public function ajax_menu_list(){
        $pid  = input('pid',0);
        if($pid){
            $data = Db::name('Menu')->where("id={$pid}")->field(true)->find();
            $this->assign('data',$data);
        }else{
            $this->assign('data',['title'=>'']);
        }
        $title      =   trim(input('get.title'));
        $type       =   config('CONFIG_GROUP_LIST');
        $all_menu   =   Db::name('Menu')->column('id,title');
        $map['pid'] =   $pid;
        if($title)
            $map['title'] = array('like',"%{$title}%");
        $page = input('post.page/d') ? : 1;
        $limit = input('post.limit/d')? : 10;
        $list       =   Db::name('Menu')->where($map)->page($page,$limit)->field(true)->order('id desc')->select();
        $count       =   Db::name('Menu')->where($map)->field(true)->count();
        int_to_string($list,array('hide'=>array(1=>'是',0=>'否'),'is_dev'=>array(1=>'是',0=>'否')));
        if($list) {
            foreach($list as &$key){
                if($key['pid']){
                    $key['up_title'] = $all_menu[$key['pid']];
                }
            }
            // p($list  );
            return json(['code' => 0 , 'msg' => '','count'=>$count,'data'=>$list , 'pid' =>$pid]);
        }else{
            return json(['code' => 200, 'msg' => '没有数据']);
        }
        
    }

    /**
     * 新增菜单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function menu_add(){
        if($_POST){
            $Menu = Db::name('Menu');
            $data = input('post.');
            if($data){
                $id = $Menu->insert($data);
                if($id){
                    action_log('update_menu', 'Menu', $id, session('user_id'));
                    //$this->success('新增成功', url('menu/index'));
                    return json(['code' => 1 , 'msg' => '新增成功']);
                } else {
                    return json(['code' => 0 , 'msg' => '新增失败']);
                }
            } else {
                $this->error($Menu->getError());
            }
        } else {
            $this->assign('info',array('pid'=>input('pid')));
            $menus = Db::name('Menu')->field(true)->select();
            $menus = Model('Common/Tree')->toFormatTree($menus);
            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            $info['hide'] = 0;
            $info['is_dev'] = 0;
            $info['pid'] = input('get.pid/d') ? input('get.pid/d') : 0;
            $this->assign('info',$info);
            $this->assign('Menus', $menus);
            $this->meta_title = '新增菜单';
            return view();
        }
    }

    /**
     * 编辑配置
     * @author kjcx
     */
    public function menu_edit($id = 0){
        if($_POST){
            $Menu = Db::name('menu');
            $data = input('post.');
            if($data){
                if($Menu->update($data)!== false){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_menu', 'Menu', $data['id'], session('user_id'));

                    $this->success('更新成功',url('menu/index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Menu->getError());
            }

        } else {

            /* 获取数据 */
            $info = Db::name('menu')->field(true)->find($id);

            $menus = Db::name('menu')->field(true)->select();

            $menus = Model('Common/Tree')->toFormatTree($menus);

            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);

            $this->assign('Menus', $menus);

            $this->assign('info', $info);

            return view('menu_add');
        }
    }

    /**
     * 删除后台菜单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function Menu_del(){
        $id = array_unique((array)input('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(Db::name('Menu')->where($map)->delete()){
            // S('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_menu', 'Menu', $id, session('user_id'));
            return json(['code'=>1,'msg'=>'删除成功']);
        } else {
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }
}
