<?php

/* * ******************************************************************************************
 * Config file where database and path config for application 
 * ---------------------------------------------------------------
 * @application        Smart File Manager(SMF)
 * @Version            smf v 1.0 ()
 * @download link      https://github.com/pclanguage/autofy_file_manager
 * @help link          
 * ---------------------------------------------------------------
 * @author             Md. saidul haque
 * @see                http://saidulhaque.com
 * ****************************************************************************************** */

$debug = true;

ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');
if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$datetime_format = 'd.m.y H:i';
// database included
define('IS_DATABASE_INCLUDED', false);

// Array of files and folders excluded from listing
// e.r array('myfile.html', 'personal-folder')
$GLOBALS['exclude_items'] = array('index.php');
// max upload file size
define('MAX_UPLOAD_SIZE', '2048');
define('FILE_NAME_PREFIX', '');
define('afm_DEBUG', false);

$afm_image_extension = array('gif', 'png', 'jpeg', 'jpg', 'JPEG', 'JPG', 'PNG', 'GIF', "webp");
$afm_document_extension = array('doc', 'docx', 'pdf', 'xls', 'ppt', 'pptx', "xml");
$afm_video_extension = array('mp3', 'mp4', 'flv');
$allowed_extensions = json_encode(array_merge(array_merge($afm_image_extension, $afm_document_extension), $afm_video_extension));

defined('FM_SHOW_HIDDEN') || define('FM_SHOW_HIDDEN', true);
//defined('FM_ROOT_PATH') || define('FM_ROOT_PATH', $root_path);
//defined('FM_LANG') || define('FM_LANG', $lang);
defined('FM_EXTENSION') || define('FM_EXTENSION', $allowed_extensions);
define('FM_READONLY', false);
define('FM_IS_WIN', DIRECTORY_SEPARATOR == '\\');
defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $os_win = true;
    $ds = '\\';
} else {
    $os_win = false;
    $ds = '/';
}
define('OS_WIN', $os_win);
define('DS', $ds);

//include_once 'lib/Pagination.php';
//include_once 'lib/common.php';
include_once 'lib/fm.php';

// Base URL (keeps this crazy sh*t out of the config.php
if (isset($_SERVER['HTTP_HOST'])) {
    $afm_base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
    $afm_base_url .= '://' . $_SERVER['HTTP_HOST'];
    $afm_base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

    // Base URI (It's different to base URL!)
    $afm_base_uri = parse_url($afm_base_url, PHP_URL_PATH);
    if (substr($afm_base_uri, 0, 1) != '/')
        $afm_base_uri = '/' . $afm_base_uri;
    if (substr($afm_base_uri, -1, 1) != '/')
        $afm_base_uri .= '/';
} else {
    $afm_base_url = 'http://localhost/';
    $afm_base_uri = '/';
}
$afm_base_url = str_replace("afm/src/server/php/actions/", "", $afm_base_url);
$afm_base_uri = trim(str_replace("afm/src/server/php/actions/", "", $afm_base_uri), "/") . "/";
$afm_server_root = $_SERVER["DOCUMENT_ROOT"] . '/';

$afm_base_uri = $afm_base_uri == DS ? "" : $afm_base_uri;

$c_dir = isset($_REQUEST['c_dir']) ? $_REQUEST['c_dir'] : "";
$c_dir = str_replace("dashboard", "", $c_dir);
$current_directory = trim($c_dir, "//") . "/";

if (OS_WIN) {
    $afm_server_root = str_replace("//", "/", $afm_server_root);
} else {
    $afm_server_root = "/" . $afm_server_root;
}
$afm_server_root = str_replace("//", "/", $afm_server_root);

$current_base_url = trim($afm_base_url . "/" . $c_dir, "//") . "/";
$current_base_url = str_replace("//", "/", $current_base_url);
$current_base_url = str_replace(":/", "://", $current_base_url);

define('afm_SERVER_ROOT', $afm_server_root); // searver root
define('afm_BASE_URI', $afm_base_uri); // after searver root, project name
define('afm_CURRENT_DIRECTORY', $current_directory); // project name name, next folder dir
define('afm_DIRECTORY_PATH', afm_SERVER_ROOT . afm_BASE_URI . $current_directory); //final dir path 

if (IS_DATABASE_INCLUDED) {
    include_once 'db/MysqliDb.php';
    include_once 'db_config.php';
}



if (afm_DEBUG) {
    echo '$afm_base_url=' . $afm_base_url . '<br/>';
    echo '$afm_base_uri=' . $afm_base_uri . '<br/>';
    echo '$afm_server_root=' . $afm_server_root . '<br/>';
    echo '$current_directory=' . $current_directory . '<br/>';
    echo '$current_base_url=' . $current_base_url . '<br/>';
}
//echo "<pre>";
$errors = [];
$response = [];
if (!$current_directory) {
    $response['error'] = "There is no assign directory path. Please set directory path where file will save or get";
    echo json_encode($response);
    exit;
} 