<?php
namespace app\admin\model;

class Store extends Common
{

    private $table = 'store';

    public function __construct(){

        parent::__construct($this->table);
    }
}