<?php
define('FACEBOOK_SDK_V4_SRC_DIR', '../vendor/fb/src/Facebook/');
require '../vendor/fb/autoload.php';
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
session_start();
include_once("../includes/head.html");

include_once("../includes/header.html");
//if a problem was submitted...
 if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['desired-address']) && isset($_POST['description'])) {
        try {
            $db = new PDO("mysql:host=localhost;dbname=rps;charset=utf8", "root", "");

            if (isset($_POST['lat']) && is_numeric($_POST['lat']) &&
            isset($_POST['long']) && is_numeric($_POST['long'])
            ) {
                $lat = $_POST['lat'];
                $long = $_POST['long'];
                if (strlen($_POST['description']) < 5 || strlen($_POST['desired-address']) < 2) {
                    throw new Exception("Описанието или адресът са прекалено кратки.");
                }
                if (empty($_SESSION['captcha']) || strtolower(trim($_REQUEST['captcha'])) != $_SESSION['captcha']) {
                    throw new Exception("Кодът от картинката е невалиден.");
                }
                $desc = htmlspecialchars($_POST['description']);
                $email = $_POST['email'] ? htmlspecialchars($_POST['email']) : "" ;
                $gsm = $_POST['gsm'] ? htmlspecialchars($_POST['gsm']) : "" ;
                $author =  $_POST['author'] ? htmlspecialchars($_POST['author']) : "" ;
                $address = htmlspecialchars($_POST['desired-address']);
                $category = htmlspecialchars($_POST['category']);
                $categoryId = $db->prepare("SELECT * FROM category WHERE name = :categoryName ");
                $categoryId->bindValue(":categoryName",$category);
                $categoryId->execute();
                $row = $categoryId->fetch(PDO::FETCH_ASSOC);
                $cat_id = $row['cat_id2'];
                //add image to server
                if (isset($_FILES['file'])) {

                    $file = $_FILES['file'];

                    if (substr_count($file['type'],"image")  > 0 && preg_match("/.+png|gif|jpg|jpeg/",$file['type'])) {

                        if (move_uploaded_file($file['tmp_name'], "../img/users/" . $file['name'])) {
                            $path = "/img/users/" . $file['name'];
                        }
                    }
                }
                //report the problem in the fb page
               $path =  (!isset($path) || !$path) ? "" : $path;

                $app_secret = "1365837abdad0597d30b9b640f1ff8fc";
                FacebookSession::setDefaultApplication('426342957490937', $app_secret );
                $message = "";
                if (isset($author)) {
                    $message .= "$author докладва за проблем. ";

                }

                $message .= "Докладван е проблем на " . $address . ".";
                $message .= " Отнася се за: $category.";
                $message .= " Описание на проблема: $desc.";
                $message .= " Линк към снимка: " . "http://" . $_SERVER['SERVER_NAME'] . $path;

                //ENTER ACCESS TOKEN
                $access_token = "CAAGDwbSIovkBACWYkA6XMlNvJiU6vlSmcy4BzZAtQH4RByjqxOy7ll5JKvS7HZAhpGJ3YVKayV5Wjdpse0TK70OJHAGgjZAZACQKp616bKRtNfQZAOGMrGUp6SnZBgJd0H9DjSHuS0Fq8qnxIjDZAOGJpNbiJ1UWiUm8EwercWhErRL7bkgaTiGaajiFWIsGuD4k9a9FnzOGxwBXWvBufg5";
               $session = new FacebookSession($access_token);
                FacebookSession::enableAppSecretProof(false);


                $request = new FacebookRequest(
                    $session,
                    'POST',
                    '/867298010006796/feed',
                    array (
                        'message' => $message
                    )
                );
                $response = $request->execute();
                $graphObject = $response->getGraphObject();


                $query = "INSERT INTO problems (author,email,gsm,cat_id,description,latitude,longitude,imagelink,address,active) VALUES (:author,:email,:gsm,:cat_id,:description,:latitude,:longitude,:imagelink,:address, 1)";

                $query = $db->prepare($query);
                $query->bindValue(":cat_id", $cat_id);
                $query->bindValue(":description", $desc);
                $query->bindValue(":latitude", $lat);
                $query->bindValue(":longitude", $long);
                $query->bindValue(":imagelink", $path);
                $query->bindValue(":address", $address);
                $query->bindValue(":author", $author );
                $query->bindValue(":email", $email);
                $query->bindValue(":gsm", $gsm);

                $query->execute();
                header("Location: /");

            }
            else {//tampered lat/long
               throw new Exception("Базарал си lat/long?");
            }
        }
        //show errors in modal
        catch (Exception $e) { ?>
            <div class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Опитайте пак...</h4>
              </div>
              <div class="modal-body">
                <p><?php echo $e->getMessage(); ?></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

            <script>
                       $(".modal").modal();
                 </script>

            <?php

        }

    }
}

//if get parameter category is set display the form to submit a problem
if (isset($_GET['category'])) {
    ?>

    <div class="col-md-6 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-12">
      <p class="lead">  Докладване на проблем относно <span class="label label-warning problem-area">
              <?php echo ucwords(htmlspecialchars($_GET['category'])); ?> </span></p>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="author">Автор:</label>
            <input id="author" type="text" name="author" class="form-control"/>
            <label for="desired-address">Адрес: <span class="glyphicon glyphicon-asterisk"></span></label>
            <input type="text" required class="form-control" name="desired-address" id="desired-address" />
            <label for="description">Описание: <span class="glyphicon glyphicon-asterisk"></span></label>
            <textarea name="description" minlength="5" class="form-control" required  id="description" cols="30" rows="10"></textarea>
            <label for="file">Прикачи снимка</label>
            <input type="file" class="form-control" name="file" id="file"/>
            <input type="hidden" id="lat" value='' name="lat"/>
            <input type="hidden" id="long" value='' name="long"/>
            <input type="hidden" name="category" id="category" value="<?php echo htmlspecialchars($_GET['category']); ?>"/>
            <label for="gsm">Телефон:</label>
            <input id="gsm" type="number" name="gsm" class="form-control"/>
            <label for="email">Електронна поща:</label>
            <input id="email" type="email" name="email" class="form-control"/>
            <label for="captcha-input">CAPTCHA: <span class="glyphicon glyphicon-asterisk"></span></label>
            <input type="text" name="captcha" class="form-control" id="captcha-input"/>

           <div class="captcha">
            <img name="captcha" src="../vendor/captcha.php" alt="CAPTCHA"/>
               </div>
            <a class="captcha-replay" href="JavaScript:void(0)">Не можеш да прочетеш кода от картинката?</a>
            <br>
            <input type="submit" value="Изпрати" class="btn btn-lg btn-default"/>

        </form>
    </div>


    <p class="lead">Моля преместете маркера, за да изберете адрес.</p>
    <div class="col-md-6">

        <div id='mapCanvas' class="col-md-12">
    </div>



    <script src="../js/problem-map.js"></script>


<?php
    }
include_once("../includes/footer.html");