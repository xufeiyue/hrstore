<?php
namespace app\home\model;

class Problem extends Common
{

    private $table = 'problem';

    public function __construct(){

        parent::__construct($this->table);
    }
}