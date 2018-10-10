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

        self::determineView();
    }

    public function index(){

        self::determineView()->loadView();
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
                '{model}'=>$result['model'],
                '{picture}'=>$result['picture'],
                '{price}'=>$result['price'],
                '{description}'=>$result['description'],
                '{item_id}'=>$result['item_id'],
                '{vendor}'=>$result['vendor'],
                '{category}'=>$result['category_name'],
                '{shop}'=>$result['shop_name'],
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
                ->unsetStreamData()
                ->getXMLObject();

            $shop = new Shop();
            $shop->insert([
                'name'=>$this->xml->shop->name,
                'company_name'=>$this->xml->shop->company,
                'company_url'=>$this->xml->shop->url], true);

            $category = new Category();
            $category->shop_id = $shop->getLastInsertId();
            $category->batchInsert($this->xml->shop->categories->category);

            $offer = new Offer();
            $offer->batchInsert($this->xml->shop->offers->offer);
        }

        $this->loadView('import');
    }

    public function runAction(){

        self::determineAction();
        call_user_func([$this, $this->_currentAction]);
    }

    public function determineAction(){

        $this->_currentAction = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : self::getDefaultAction();
    }

    public function determineView(){

        $this->_currentView = (isset($_REQUEST['r'])) ? $_REQUEST['r'] : self::getDefaultView();
        return $this;
    }

    public function loadView($view=null, array $parseData=[]){

        if($view)
            $this->_currentView = $view;

        $path = dirname(__DIR__) .$this->_pathView .DIRECTORY_SEPARATOR ."{$this->_currentView}.php";
        $data = file_get_contents($path);

        if(!empty($parseData)){

            $search = array_keys($parseData);
            $parseData = array_flip($parseData);
            $replace= array_keys($parseData);

            $data = str_replace($search, $replace, $data);
        }
        print $data;
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