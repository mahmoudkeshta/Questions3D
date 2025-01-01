<?php 
include './connect.php';

$id=filterRequest("id");

getAllData("teacher_domain_marks"," id = ?",array($id));

?>
