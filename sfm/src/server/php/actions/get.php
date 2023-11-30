<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once '../config.php';

$items = [];
$pageLimit = 10;
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : '0';
$page = $page ? $page : 1;


$extension = isset($_REQUEST['extension']) && $_REQUEST['extension'] ? $_REQUEST['extension'] : '';
$extension = ($extension == '*' || $extension == 'All') ? "" : $extension;
$keyword = isset($_REQUEST['keyword']) && $_REQUEST['keyword'] ? $_REQUEST['keyword'] : '';
// echo SFM_DIRECTORY_PATH; 
$file_folders = get_file_folders(SFM_DIRECTORY_PATH);
//print_r($file_folders);  exit; 
$files = $file_folders['files'];
$folders = $file_folders['folders'];
$total_files = count($files);
$total_folders = count($folders);
$total_rows = $total_files + $total_folders;

if ($total_folders) {
    foreach ($folders as $folder) {
        $folder_info['name'] = $folder;
        $folder_info['size'] = "Folder";
        $folder_info['type'] = "folder";
        $folder_info['label'] = $folder;
        $is_link = is_link(SFM_DIRECTORY_PATH . $folder);
        $folder_info['icon'] = $is_link ? 'icon-link_folder' : 'fa-folder-o';


        $folder_info['modified'] = date(FM_DATETIME_FORMAT, filemtime(SFM_DIRECTORY_PATH . $folder));
        $items[] = $folder_info;
    }
}


$temp_files = [];
if ($total_files) {
    foreach ($files as $file) {
        $file_path = SFM_DIRECTORY_PATH . $file;
        $file_info = pathinfo($file_path);
        $file_info['name'] =$filename= $file_info['basename'];
        unset($file_info['dirname']);
        unset($file_info['basename']);
//        echo $current_directory . $file; exit;
        $file_info['size'] = fm_get_filesize(fm_get_size($file_path));

        $file_info['type'] = "file";
        $is_link = is_link($file_path);
        $file_info['icon'] = $is_link ? 'fa-file-text-o' : fm_get_file_icon_class($file_path);


        $file_info['label'] = $file;
        $file_info['modified'] = date(FM_DATETIME_FORMAT, filemtime($file_path));
        $file_info['file_path'] = $current_base_url .$filename;
        


        $items[] = $file_info;
    }
}





if (IS_DATABASE_INCLUDED) {
    
}

//    print_r($items);
//    exit;
//if ($total_rows) {
$limit = 10;
$pages = ceil($total_rows / $limit);

$currentPageItem = 10;

$paging = [];
$paging['total'] = $total_rows;
$paging['total_folders'] = $total_folders;
$paging['total_files'] = $total_files;
$paging['per_page'] = 10;
$paging['pages'] = $pages;
$paging['limit'] = $limit;
$paging['page'] = $page;
$paging['currentPageItem'] = $total_rows;



$response["paging"] = $paging;
$response["items"] = $items;
$response["status"] = 200; // success code 
// encode response 
//} else {
//    $response['error'] = "There is no file found";
////    $image_path
//}


echo json_encode($response);
//if (empty($errors) && !empty($response)) {
//    echo json_encode($response);
//} else {
//    echo json_encode($errors);
//}
?>