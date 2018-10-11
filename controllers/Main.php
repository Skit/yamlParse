<?php

namespace controllers;

$modelsPath = dirname(__DIR__).DIRECTORY_SEPARATOR .'models' .DIRECTORY_SEPARATOR;

include_once "{$modelsPath}LoadXML.php";
include_once "{$modelsPath}Shop.php";
include_once "{$modelsPath}Category.php";
include_once "{$modelsPath}Offer.php";

use models\LoadXML;
use models\Shop;
use models\Category;
use models\Offer;

class Main
{
    public $xml;

    private
        $_defaultView = 'search',
        $_defaultAction = 'index',
        $_pathView = DIRECTORY_SEPARATOR .'views',
        $_currentView,
        $_currentAction,
        $_time;

    public function __construct()
    {
        $this->_time = microtime(true);

        self::determineAction();
    }

    public function index(){

        self::loadView('search');
    }

    public function item(){

        if(isset($_POST['model'])){

            $items = new Offer();

            $result = $items->select('items.*, c.name as category_name, s.name as shop_name')
                ->leftJoin('category c ON c.category_id=items.category_id')
                ->leftJoin('shop s ON c.shop_id=s.id')
                ->whereLike('items.model like ?', $_POST['model'])->one();
        }
        if($result){

            $this->loadView('item', [
                'model'=>$result['model'],
                'picture'=>$result['picture'],
                'price'=>$result['price'],
                'description'=>$result['description'],
                'item_id'=>$result['item_id'],
                'vendor'=>$result['vendor'],
                'category'=>$result['category_name'],
                'shop'=>$result['shop_name'],
            ]);
        }else{

            $this->loadView('noItem');
        }
    }

    public function load(){

        if(preg_match('~^(https?:\/\/)?[\w\d-_]+.[\w]{2,5}[\/\w\d\.]*~iu', $_POST['url']) == 1){

            $this->xml = (new LoadXML($_POST['url']))
                ->checkFormat()
                ->saveStream()
                ->getXMLObject();

            $shop = new Shop();
            $shop->insert([
                'name'=>$this->xml->shop->name,
                'company_name'=>$this->xml->shop->company,
                'company_url'=>$this->xml->shop->url], true);

            $category = new Category();
            $category->shop_id = $shop->getLastInsertId();
            $category->batchInsert($this->xml->shop->categories->category, true);

            $offer = new Offer();
            $offer->batchInsert($this->xml->shop->offers->offer, true);

            $categoryCount = $this->xml->shop->categories->category->count();
            $offerCount = $this->xml->shop->offers->offer->count();

            $message = urlencode("\"{$this->xml->shop->name}\" обработано: {$categoryCount} категорий, {$offerCount} товаров");

            $this->redirect('load', "&m={$message}");
        }

        $this->loadView('import');
    }

    public function runAction(){

        call_user_func([$this, $this->_currentAction]);
    }

    public function determineAction(){

        $this->_currentAction = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : self::getDefaultAction();
    }


    public function redirect($action, $params=''){

        header("Location: /index.php?a={$action}{$params}");
    }

    public function loadView($view=null, array $data=[]){

        if($view)
            $this->_currentView = $view;

        $path = dirname(__DIR__) .$this->_pathView .DIRECTORY_SEPARATOR ."{$this->_currentView}.php";

        ob_start();
        include_once ($path);
        $view = ob_get_contents();
        ob_end_clean();

        print $view;
    }

    public function getDefaultView(){

        return $this->_defaultView;
    }

    public function getDefaultAction(){

        return $this->_defaultAction;
    }

    public function __destruct()
    {
        echo '<pre>',
            round(microtime(true) - $this->_time, 4),
            '</pre>';
    }
}