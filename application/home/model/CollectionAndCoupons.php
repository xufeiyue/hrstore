<?php
namespace app\home\model;
use think\Db;
class CollectionAndCoupons extends Common
{

    private $table = 'collection_and_coupons';

    public function __construct(){

        parent::__construct($this->table);
    }

    //查询收藏并且未到期的商品
    public function collection_goods($where=[],$order=[],$field=[]){

    	$data = Db::name($this->table)
    		->alias('c')
    		->join('goods g','g.id = c.goods_id')
    		->where($where)
    		->field($field)
    		->order($order)
    		->select();

    	return $data;
    }

    //查询收藏并且到期的商品
    public function collection_id($where=[],$order=[],$field=[]){

    	$data = Db::name($this->table)
    		->alias('c')
    		->join('goods g','g.id = c.goods_id')
    		->where($where)
    		->field($field)
    		->order($order)
    		->column('c.id');

    	return $data;
    }
}