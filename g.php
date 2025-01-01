<?php
include './connect.php';

    global $con;
    $data = array();

        $stmt = $con->prepare("SELECT  * FROM myq ");
 

    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
 
    if ($count > 0){
        echo json_encode(array("status" => "success", "data" => $data));
    } else {
        echo json_encode(array("status" => "failure"));
    }
    return $count;

    if($count>0){
        return $data;
    }else{
        return          json_encode(array("status" => "failure"));
    }


  


