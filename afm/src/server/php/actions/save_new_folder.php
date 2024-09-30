<?php

include_once '../config.php';
//print_r($_POST); exit; 
$folder_name = isset($_POST['folder_name']) ? $_POST['folder_name'] : "";
$is_directory = isset($_POST['is_directory']) && $_POST['is_directory'] ? $_POST['is_directory'] : false;

//echo $is_directory; exit;
$response["status"] = 400; // by default error
if (!$folder_name) {

    $response["errors"] = "file name is requred";
} else {
     $folder_name = $is_directory=="true"?$folder_name:generate_alias($folder_name, "-");
        $current_directory =   $current_directory. $folder_name;
    try {
        fm_mkdir($current_directory, true);
        $response["status"] =200; 
    } catch (Exception $exc) {
        //echo $exc->getTraceAsString();
        $response["errors"] = $exc->getTraceAsString();
    }
}



echo json_encode($response);

//fm_mkdir($current_directory, true);