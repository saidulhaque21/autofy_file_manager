<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once '../config.php';
// print_r($_POST); 

$name = isset($_POST['name']) && $_POST['name'] ? $_POST['name'] : "";
$is_derectory = isset($_POST['is_derectory']) && $_POST['is_derectory'] ? $_POST['is_derectory'] : "";

$response["status"] = 400; // by default error
if (!$name) {
    $response["errors"] = "file path is requred";
} else {
    try {
        if ($is_derectory) {
             fm_rdelete(afm_DIRECTORY_PATH );
        } else {
            fm_rdelete(afm_DIRECTORY_PATH . $name);
        }

        $response["status"] = 200;
    } catch (Exception $exc) {
        $response["errors"] = $exc->getTraceAsString();
    }
}




echo json_encode($response);
?>