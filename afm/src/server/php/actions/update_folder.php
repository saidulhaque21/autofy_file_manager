<?php

include_once '../config.php';

$folder_name = isset($_POST['folder_name']) ? $_POST['folder_name'] : "";
$previous_name = isset($_POST['previous_name']) ? $_POST['previous_name'] : "";
//print_r($_POST); 

$response["status"] = 400; // by default error
if (!$folder_name) {
    $response["errors"] = "file name is requred";
} else {
    $new_name = generate_alias($folder_name);
    $new = str_replace($previous_name, $new_name, afm_DIRECTORY_PATH);
    try {
        fm_rename(afm_DIRECTORY_PATH, $new);

        $folder_info['name'] = $new_name;
        $folder_info['size'] = "Folder";
        $folder_info['type'] = "folder";
        $folder_info['label'] = $new_name;

        $response["status"] = 200;
        $response["item"] = $folder_info;
    } catch (Exception $exc) {
        //echo $exc->getTraceAsString();
        $response["errors"] = $exc->getTraceAsString();
    }
}

echo json_encode($response);
//fm_mkdir($current_directory, true);