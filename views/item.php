<!DOCTYPE html>
<html>
<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link href="../web/main.css" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    <nav class="navigation">
        <ul>
            <li>
                <a href="/index.php?a=load">Import</a>
            </li>
            <li class="last">
                <a href="/index.php?a=index">Search</a>
            </li>
        </ul>
    </nav>
</div>
<div class="container bg-faded">
    <h2>Информация о товаре #<?=$data['item_id']?></h2>
    <div class="row">
        <div class="col-4 item-photo">
            <img class="img-fluid rounded" src="<?=$data['picture']?>" />
        </div>
        <div class="col-xs-5" style="border:0px solid gray">
            <h3><?=$data['model']?></h3>
            <h5 style="color:#337ab7"></small></h5>
            <h5 style="color:#337ab7">Поставщик: <?=$data['vendor']?></small></h5>

            <!-- Precios -->
            <h6 class="title-price"><small>#<?=$data['item_id']?></small></h6>
            <h3 style="margin-top:0px;">RUB: <?=$data['price']?></h3>

            <div class="section" style="padding-bottom:5px;">
                <div>
                    <div class="attr2">Магазин: <?=$data['shop']?></div>
                    <div class="attr2">Категория: <?=$data['category']?></div>
                </div>
            </div>
        </div>

        <div class="col-xs-9">
            <div style="width:100%;border-top:1px solid silver">
                <p style="padding:15px;">
                    <small><?=$data['description']?></small>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>