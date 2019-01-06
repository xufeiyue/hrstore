<?php
namespace app\home\model;
use think\Db;
class BrowsingLog extends Common
{

    private $table = 'browsing_log';

    public function __construct(){

        parent::__construct($this->table);
    }

    //浏览记录
    public function zuji_goods($where=[],$order=[],$field=[]){

    	$data = Db::name($this->table)
    		->alias('b')
    		->join('goods g','g.id = b.pid')
    		->where($where)
    		->order($order)
    		->field($field)
    		->select();

    	return $data;
    }

    //查询浏览记录并且到期的商品记录
    public function zuji_id($where=[],$order=[],$field=[]){

    	$data = Db::name($this->table)
    		->alias('b')
    		->join('goods g','g.id = b.pid')
    		->where($where)
    		->order($order)
    		->field($field)
    		->column('b.id');

    	return $data;
    }
}