<?php
namespace app\home\model;
use think\Db;
class GoodsType extends Common
{

    private $table = 'goods_type';

    public function __construct(){

        parent::__construct($this->table);
    }

    public function recommend_type($offset=0,$limit=10,$where=[],$order=[],$field=[],$store_id=0){

    	$data = Db::name($this->table)
    		->alias('g')
    		->join('store_type_recommend s',"s.type_id = g.id and s.store_id = {$store_id}",'left')
    		->join('store_type_sort t',"t.type_id = g.id and s.store_id = {$store_id}",'left')
			->where($where)
			->limit($offset,$limit)
			->order($order)
			->field($field)
			->select();

		return $data;
    }

    //全部分类+排序
    public function goods_type_all($where=[],$order=[],$field=[],$store_id=0){

    	$data = Db::name($this->table)
    		->alias('g')
    		->join('store_type_recommend s',"s.type_id = g.id and s.store_id = {$store_id}",'left')
    		->join('store_type_sort t',"t.type_id = g.id and s.store_id = {$store_id}",'left')
			->where($where)
			->order($order)
			->field($field)
			->select();

		return $data;
    }
}