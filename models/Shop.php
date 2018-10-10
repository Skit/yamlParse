<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 08.10.2018
 * Time: 17:48
 */
namespace models;

use model\Query;

include_once 'Query.php';

class Shop extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function types(){

        return [
            'name'=>'string',
            'company_name'=>'string',
            'company_url'=>'string'
        ];
    }

    public function table()
    {
        return 'shop';
    }
}