<?php
namespace app\home\model;
use think\Db;
class Activity extends Common
{

    private $table = 'activity';

    public function __construct(){

        parent::__construct($this->table);
    }

    public function get_activity_goods($w){
        return DB::name('activity_goods')->where($w)->find();
    }
}