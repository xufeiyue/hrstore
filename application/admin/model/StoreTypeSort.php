<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/24
 * Time: 13:40
 */
namespace app\admin\model;
use think\Db;
class StoreTypeSort extends Common
{
    private $table = 'store_type_sort';

    public function __construct(){

        parent::__construct($this->table);
    }


}