<?php
$db = new PDO("mysql:host=localhost;dbname=rps;charset=utf8", "root", "");
if (isset($_GET['confirm']) && isset($_GET['problem']) && is_numeric($_GET['problem'])) {
    $ip = getIP();
    $query = "SELECT * FROM confirmation WHERE ip = :ip AND problem_id = :problem";
    $hasIp = $db->prepare($query);
    $hasIp->bindValue(":ip",$ip);
    $problem = $_GET['problem'];
    $hasIp->bindValue(":problem",$problem);
    $hasIp->execute();
    //if the ip has already liked this problem and he has clicked again - remove his like
    if ($hasIp->fetch()) {

        $query = "DELETE FROM confirmation WHERE ip = :ip AND problem_id = :problem";
        $new = $db->prepare($query);
        $new->bindValue(":problem", $problem);
        $new->bindValue(":ip", $ip);
        echo $new->execute();

    }
    else {
        //if no existing ip is found for the problem - add his vote
        $query = "INSERT INTO confirmation (problem_id,ip,likes) VALUES (:problem,:ip, 1)";
        $new = $db->prepare($query);
        $new->bindValue(":problem", $problem);
        $new->bindValue(":ip", $ip, PDO::PARAM_STR);
        $new->execute();
    }




}

else if (isset($_GET['unconfirm']) && isset($_GET['problem']) && is_numeric($_GET['problem'])) {
    $ip = getIP();
    $query = "SELECT * FROM confirmation WHERE ip = :ip AND problem_id = :problem";
    $hasIp = $db->prepare($query);
    $hasIp->bindValue(":ip",$ip);
    $problem = $_GET['problem'];
    $hasIp->bindValue(":problem",$problem);
    $hasIp->execute();
    //if users disliked already and clicked on dislike - remove his dislike
    if ($hasIp->fetch()) {
        $query = "DELETE FROM confirmation WHERE ip = :ip AND problem_id = :problem";
        $new = $db->prepare($query);
        $new->bindValue(":problem", $problem);
        $new->bindValue(":ip", $ip);
        $new->execute();
    }
    else {
        //if he clicks on dislike for the first time - add a dislike
        $query = "INSERT INTO confirmation (problem_id,ip,likes) VALUES (:problem,:ip, 0)";
        $new = $db->prepare($query);
        $new->bindValue(":problem", $problem);
        $new->bindValue(":ip", $ip);
        $new->execute();
    }
    }



//get client ip
function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}