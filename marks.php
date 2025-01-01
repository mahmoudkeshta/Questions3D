<?php 

include './connect.php';

$teachers_id = filterRequest('teachers_id');
$dome_id = filterRequest('dome_id');
$mark_value = filterRequest('mark_value');
$percentage_value = filterRequest('percentage_value');
$question_id = filterRequest('question_id');



    $data = array(
        "teachers_id" => $teachers_id,
        "dome_id_m" => $dome_id,
        "mark_value" => $mark_value,
        "percentage_value" => $percentage_value,
               "question_id" => $question_id,
    );
  
    insertData("dome_marks", $data);
