<?php
namespace app\home\model;

class CollectionAndCoupons extends Common
{

    private $table = 'collection_and_coupons';

    public function __construct(){

        parent::__construct($this->table);
    }
}