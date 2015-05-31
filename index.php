<?php
include_once("includes/head.html");

include_once("includes/header.html");

?>


<div class="col-md-4 col-sm-10 col-xs-12">


<ul class="list-group">

    <?php
    $db = new PDO("mysql:host=localhost;dbname=rps;charset=utf8", "root", "");
    $query = "SELECT * FROM category";
    $allCategories = $db->query($query);

    while ($row = $allCategories->fetch()) { ?>
        <?php

        ?>
        <li class="list-group-item"><a href="problem/add-problem.php?category=<?php echo urlencode($row['name']); ?>"><?php echo $row['name']; ?></a></li>
    <?php
    }

    ?>

</ul>
</div>
<div class="col-md-8 col-sm-12">
    <div id='all-map' class="col-md-12">

    </div>


</div>

<div class="jumbotron specific-problem text-center col-md-6 col-xs-12 col-md-offset-3">
    <p class="date-text pull-left"><span class="glyphicon glyphicon-time"></span> Публикувано на: <span class="problem-date"></span> </p>

    <p class='confirmation pull-right'><span class="confirm-now">Потвърдили: <span class="confirmed-box">0</span> <span class="glyphicon glyphicon-thumbs-up"></span>
           </span>
        <span class="unconfirm-now"> Отрекли: <span class="unconfirmed-box">0</span> <span class="glyphicon glyphicon-thumbs-down"></span> </span>
    </p>
    <h2 class="text-center">Tип проблем: <span class="problem-category"></span> </h2>

    <img class="decorative-border" src="img/decorative-border.png" alt="Border">

    <h3><span class="glyphicon glyphicon-warning-sign"></span> Адрес: <span class="problem-address"></span></h3>
    <p class="lead"> <span class="glyphicon glyphicon-comment"></span> Описание на проблема: <span class="problem-description"></span></p>
    <p class="author lead"><span class="glyphicon glyphicon-user"></span>Подател: <span class="author-name"></span>    </p>
    <p class="text-center"><span class="glyphicon glyphicon-picture"></span> Снимка на проблема:</p>
    <div class="problem-image"></div>


</div>
<?php
include_once("includes/footer.html");


?>