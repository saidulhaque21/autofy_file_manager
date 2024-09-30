<?php

$is_ci_framework = true;
//database config
$afm_hostname = "localhost";
$afm_username = 'root';
$afm_password = '';
$afm_database_name = 'website_yc';
$table_prefix = '';
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : "development");
if ($is_ci_framework) {
    $server_root = afm_SERVER_ROOT . afm_BASE_URI;
    $appPath = $server_root . 'application/';
    $apps_path = 'application/';

//include database config
//include database config
    define('BASEPATH', $server_root);
    $application_folder = $server_root . 'application/';
    if (is_dir($application_folder)) {
        include_once $application_folder . 'config/database.php';
    } else {
        $application_folder = $server_root . 'system/application/';
        if (!is_dir($application_folder)) {
            exit("Your application folder path does not appear to be set correctly. Please open the  build_code/library/config.php and correct this ");
        }

        include_once $application_folder . 'config/database.php';
    }
    $afm_hostname = $db['default']['hostname'];
    $afm_username = $db['default']['username'];
    $afm_password = $db['default']['password'];
    $afm_database_name = $db['default']['database'];
}


$database_config = Array(
    'host' => $afm_hostname,
    'username' => $afm_username,
    'password' => $afm_password,
    'db' => $afm_database_name,
    'port' => 3306,
    'prefix' => $table_prefix,
    'charset' => 'utf8');
$db = new MysqliDb($database_config);
