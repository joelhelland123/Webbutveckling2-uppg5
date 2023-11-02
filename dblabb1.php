<?php
include 'dblabb2.php';
$db = new DB();
$query = "SELECT * FROM user";
if ($result = $db->query($query)) {
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        echo "<pre>" . print_r($row, 1) . "</pre>";
    }
}
