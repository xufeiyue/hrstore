<?php
namespace app\home\model;

class Config extends Common
{

    private $table = 'config';

    public function __construct(){

        parent::__construct($this->table);
    }
}