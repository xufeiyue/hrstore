<?php
namespace app\home\model;
use think\Db;
class ActivityLog extends Common
{

    private $table = 'activity_log';

    public function __construct(){

        parent::__construct($this->table);
    }

    //列表
    public function ActivityLog_list($where=[],$order=[],$field=[]){

    	$data = Db::name($this->table)
    		->alias('a_l')
    		->join('activity a','a.id = a_l.activity_id')
    		->where($where)
    		->order($order)
    		->field($field)
    		->select();

    	return $data;
    }
}