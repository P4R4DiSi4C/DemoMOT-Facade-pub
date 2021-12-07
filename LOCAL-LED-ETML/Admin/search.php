<?php
    include "adminClass.php";
    $class = new adminClass();

    $key=$_GET['key'];
    $array = array();

    $result = $class->liveSearch($key);

    foreach($result as $row)
    {
      $array[] = $row['useUsername'];
    }
    echo json_encode($array);
?>
