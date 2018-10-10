<?php
namespace models;

use model\Query;

include_once 'Query.php';

class Category extends Query
{
    public $shop_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function table()
    {
        return 'category';
    }

    public function types(){

        return [
            'category_id'=>'integer',
            'parent_id'=>'integer',
            'name'=>'string'
        ];
    }

    public function scheme()
    {
        return [
            'category_id'=>'@id',
            'parent_id'=>'@parentId',
            'shop_id'=>$this->shop_id,
            'name'=>'#__toString',
        ];
    }

}