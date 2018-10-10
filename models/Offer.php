<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 09.10.2018
 * Time: 9:56
 */

namespace models;

use model\Query;

include_once 'Query.php';

class Offer extends Query
{
    public $shop_id;

    public function __construct()
    {
        parent::__construct();
    }

    public function table()
    {
        return 'items';
    }

    public function types(){

        return [
            'item_id'=>'int',
            'available'=>'int',
            'model'=>'str',
            'picture'=>'str',
            'price'=>'str',
            'description'=>'str',
            'vendor'=>'str',
            'category_id'=>'int',
        ];
    }

    public function scheme()
    {
        return [
            'item_id'=>'@id',
            'available'=>'@available',
            'model'=>'<model',
            'picture'=>'<picture',
            'price'=>'<price',
            'description'=>'<description',
            'vendor'=>'<vendor',
            'category_id'=>'<categoryId',
        ];
    }

}