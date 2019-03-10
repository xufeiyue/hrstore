<?php
namespace app\home\model;

use think\Db;

class Goods extends Common
{

    private $table = 'goods';

    public function __construct(){

        parent::__construct($this->table);
    }

    public function getchildgoods($id,$store_id){
    	$time = time();
        $sql = "select id,goods_name,goods_images,goods_original_price,goods_present_price from th_goods 
where type_id in (select id from th_goods_type where id = $id or pid = $id) and state = 0 and status = 0 and store_id = $store_id 
and xianshi = 0 and end_time >= $time";

        return Db::query($sql);
    }
}