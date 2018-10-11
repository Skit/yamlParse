<!doctype html>
<html>
<header>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link href="../web/main.css" rel="stylesheet">
</header>
<body>
<div class="wrapper">
    <nav class="navigation">
        <ul>
            <li>
                <a class="active" href="#">Import</a>
            </li>
            <li class="last">
                <a href="/index.php?a=index">Search</a>
            </li>
        </ul>
    </nav>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <form class="card card-sm" action="index.php?a=load" method="post">
                <div class="card-body row no-gutters align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-search h4 text-body"></i>
                    </div>
                    <!--end of col-->
                    <div class="col">
                        <input name="url" class="form-control form-control-lg form-control-borderless" type="search" placeholder="Yaml catalog URL">
                    </div>
                    <!--end of col-->
                    <div class="col-auto">
                        <button name="btn" class="btn btn-lg btn-success" type="submit">Import</button>
                    </div>
                    <!--end of col-->
                </div>
            </form>
        </div>
        <?php if(isset($_GET['m'])) : ?>
            <div id="message"><?=urldecode($_GET['m']) ?></div>
        <?php endif; ?>
        <!--end of col-->
    </div>
</div>
</body>
</html>

