<?php
//return all the problems from the database
$db = new PDO("mysql:host=localhost;dbname=rps;charset=utf8", "root", "");
$query = "SELECT * FROM problems INNER JOIN category ON problems.cat_id = category.cat_id2";
$results = $db->query($query);
$problems = $results->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($problems);
