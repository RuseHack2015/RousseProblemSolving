<?php
$db = new PDO("mysql:host=localhost;dbname=rps;charset=utf8", "root", "");
if (isset($_GET['problem']) && is_numeric($_GET['problem'])) {
    $query = "SELECT likes FROM confirmation WHERE likes = 1 AND problem_id = :problem";
    $dislikesQuery = "SELECT likes FROM confirmation WHERE likes = 0 AND problem_id = :problem";
    $problemQuery = "SELECT * FROM problems INNER JOIN category ON category.cat_id2 = problems.cat_id WHERE problems.id = :problem";
    $problemInfo = $db->prepare($problemQuery);
    $problemInfo->bindValue(":problem", $_GET['problem']);
    $problemInfo->execute();
    $problemInfoRow = $problemInfo->fetch();
    $likes = $db->prepare($query);
    $likes->bindValue(":problem",$_GET['problem']);
    $likes->execute();
    $likeys = $likes->fetchAll(PDO::FETCH_ASSOC);
    $dislikes = $db->prepare($dislikesQuery);
    $dislikes->bindValue(":problem",$_GET['problem']);
    $dislikes->execute();
    $dislikeys = $dislikes->fetchAll(PDO::FETCH_ASSOC);
    $return = array();
    $dislikeCount = 0;
    if ($dislikeys) {
        foreach ($dislikeys as $dislike) {
            foreach ($dislike as $dis) {
                $dislikeCount++;
                $return[] = $dis;
            }
        }

    }
    $likeCount = 0;
    if ($likeys) {
        foreach ($likeys as $like) {
            foreach ($like as $lik) {
                $likeCount++;
                $return[] = $lik;
            }
        }
    }
    $average = $likeCount - $dislikeCount;
    //if the likes exceed the dislikes with 5 - send an email to the mayor and set the problem to inactive so no further mails will be sent
    if ($average >= 5) {

        $query = "SELECT * FROM problems WHERE active = 1 AND id = :problem";
        $isActive = $db->prepare($query);
        $isActive->bindValue(":problem", $_GET['problem']);
        $isActive->execute();
        $row = $isActive->fetch();
        if ($row && count($row)) {
            $message = "Здравейте, г-н Кмете. Открит е проблем в района на град Русе. Той се отнася за  " . $problemInfoRow['name'] . ".";
            $authorName = ($problemInfoRow['author']) ? ", " . $problemInfoRow['author'] . ", " : "";
            $message .= " Авторът на оплакването $authorName е описал проблемът по следния начин: " . $problemInfoRow['description'];
            $message .= " Проблемът е засечен на адрес: " . $problemInfoRow['address'];

            if ($problemInfoRow['imagelink']) {
                $message .= " Линк към снимка на проблема: " . $problemInfoRow['imagelink'];
            }
            ob_start();
            require_once("sendmail.php");

            loadMessage($message);
            ob_get_clean();
        }

        $query =  "UPDATE problems SET active = 0 WHERE id = :problem";
        $removeActive = $db->prepare($query);
        $removeActive->bindValue(":problem", $_GET['problem']);
        $removeActive->execute();
    }
    echo json_encode($return);
}
