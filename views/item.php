<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-4 item-photo">
            <img style="max-width:100%;" src="{picture}" />
        </div>
        <div class="col-xs-5" style="border:0px solid gray">
            <!-- Datos del vendedor y titulo del producto -->
            <h3>{model}</h3>
            <h5 style="color:#337ab7"></small></h5>
            <h5 style="color:#337ab7">Поставщик: {vendor}</small></h5>

            <!-- Precios -->
            <h6 class="title-price"><small>#{item_id}</small></h6>
            <h3 style="margin-top:0px;">RUB: {price}</h3>

            <div class="section" style="padding-bottom:5px;">
                <div>
                    <div class="attr2">Магазин: {shop}</div>
                    <div class="attr2">Категория: {category}</div>
                </div>
            </div>
        </div>

        <div class="col-xs-9">
            <div style="width:100%;border-top:1px solid silver">
                <p style="padding:15px;">
                    <small>{description}</small>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>