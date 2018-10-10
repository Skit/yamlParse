<?php
//$start = microtime(true);
include_once 'controllers/Main.php';

use root\Parser;
use controllers\Main;
//$pars = new Parser('http://armprodukt.ru/bitrix/catalog_export/yandex.php');
//$pars = new Parser('http://armprodukt.ru/bitrix/catalog_expodfrt/yandex.php');
//$pars = new Parser('http://armata.ru');
//$pars->loadShop()->loadCategories()->loadOffers();

//print '<br />';
//print round(microtime(true) - $start, 4);

$app = new Main();
$app->runAction();