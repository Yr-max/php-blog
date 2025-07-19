<?php

require "../config/config.php";

$stmt = $db->prepare("DELETE FROM posts WHERE id=".$_GET['id']);
$stmt->execute();

header("location: index.php");
?>