<?php
namespace app\home\model;

class AdvertisementType extends Common
{

    private $table = 'advertisement_type';

    public function __construct(){

        parent::__construct($this->table);
    }
}