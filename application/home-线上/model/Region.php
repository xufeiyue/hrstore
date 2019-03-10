<?php
namespace app\home\model;

class Region extends Common
{

    private $table = 'region';

    public function __construct(){

        parent::__construct($this->table);
    }
}