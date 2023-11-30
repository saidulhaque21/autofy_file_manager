<?php

function delete_temp_files($prefix = 'ua_', $dir = '') {
    $files = glob($dir . '/' . $prefix . '*');
    if ($files) {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        return $files;
    }
}

function create_pagination($uri, $total_rows, $limit = '20', $page = 0, $base_url = '', $suffix = '') {

    $pagination = new UA_Pagination();

    $current_page = $page;

    // Initialize pagination
    $base_url = 'src/action/load';

    $config['base_url'] = $base_url . $uri . '/';

    $config['total_rows'] = $total_rows; // count all records
    $config['per_page'] = $limit;
    $config['cur_page'] = $page;
    $config['page_query_string'] = FALSE;
    $config['ajax_paging'] = true;
    // $config['prefix'] = '&fieldValue=Dhaka';
    $config['suffix'] = $suffix;
    $config['num_links'] = 4;

    $config['next_link'] = '>>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['prev_tag_open'] = '<li class="img">';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li class="img">';
    $config['next_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li class="first">';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="last">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0)">';
    $config['cur_tag_close'] = '</a></li>';
    $config['prev_link'] = '<<';
    $config['last_link'] = 'Last';
    $config['first_link'] = 'First';
    $config['full_tag_open'] = '<div  class="ua_pagination_container col-md-7" style="padding-right: 0px;"><ul class="pagination pull-right">';
    $config['full_tag_close'] = '</div></ul>';


    $pagination->initialize($config); // initialize pagination

    $paging_info = '';

    $from = $current_page + 1;
    $to = $current_page + $limit;
    if ($total_rows <= $to)
        $to = $total_rows;

    if ($total_rows) {
        $paging_info = '<div class="paging_info col-md-5">Record vew ' . $from . ' to  ' . $to . ' of ' . $total_rows . '</div>';
    }
    return array(
        'current_page' => $current_page,
        'per_page' => $config['per_page'],
        'limit' => array($config['per_page'], $current_page),
        'paging_info' => $paging_info,
        'links' => $pagination->create_links()
    );
}

function clear_temp() {
    $captcha_path = SERVER_ROOT . "includes/captcha/";
    $handle = opendir($captcha_path);
    while (false !== ($file = readdir($handle))) {
        if ($file {0} != '.') {
            unlink($captcha_path . $file);
        }
    }
    closedir($handle);
}

function get_extension($root, $fileName, $ua_image_extension) {
    $extension_info = array();
    $expload = explode('.', $fileName);
    $extension = end($expload);
    $extension_info['extension'] = $extension;
    $fileicon = '';
    if (!in_array($extension, $ua_image_extension)) {
        $icon_dir = $root . 'icons/';
        $filePath = UA_ROOT . 'icons/' . $extension . '.png';
        if (file_exists($filePath)) {
            $fileicon = $icon_dir . $extension . '.png';
        } else {
            $fileicon = $icon_dir . 'file.png';
        }
    }
    $extension_info['fileicon'] = $fileicon;

    return $extension_info;
}

function get_ua_file_folder($cdb, $ua_upload_directory = '', $ua_logged_user_id = '') {
    $now = date("Y-m-d H:i:s");
    $ua_upload_directories = explode('/', $ua_upload_directory);
    //print_r($ua_upload_directories);
    $folder_id = 1;
    if (!empty($ua_upload_directory)) {
        foreach ($ua_upload_directories as $upload_directory) {
            $cdb->where('name', $upload_directory);
            if ($folder_id > 1) {
                $cdb->where('user_id', $ua_logged_user_id);
            }
            $file_folder = $cdb->getOne('file_folders');
            // print_r($file_folder); exit;
            if (!empty($file_folder)) {
                $folder_id = $file_folder['id'];
            } else {
                $data = array(
                    'name' => $upload_directory,
                    'alias' => strtolower($upload_directory),
                    'user_id' => $ua_logged_user_id,
                    'parent_id' => $folder_id,
                    'created' => $now,
                    'updated' => $now,
                );

                $folder_id = $cdb->insert('file_folders', $data);
            }
            //echo $folder_id . '<br/>';
        }
    }

    return $folder_id;
}

function make_new_directory($root, $path) {
    $location_array = explode('/', $path);
    $first = $root;
    foreach ($location_array as $location_val) {
        if ($location_val != '') {
            if (!file_exists($first . '/' . $location_val))
                mkdir($first . '/' . $location_val);
            $first = $first . '/' . $location_val;
        }
    }
    return true;
}

function generate_code($length = 10, $only_number = false, $prefix = '') {
    if ($length <= 0) {
        return false;
    }
    $code = "";
    if ($only_number) {
        $chars = "0123456789";
    } else {
        $chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
    }
    srand((double) microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $code = $code . substr($chars, rand() % strlen($chars), 1);
    }
    return $prefix . $code;
}

function youtube_id_from_url($url) {
    $pattern = '%^# Match any youtube URL
    (?:https?://)?  # Optional scheme. Either http or https
    (?:www\.)?      # Optional www subdomain
    (?:             # Group host alternatives
      youtu\.be/    # Either youtu.be,
    | youtube\.com  # or youtube.com
      (?:           # Group path alternatives
        /embed/     # Either /embed/
      | /v/         # or /v/
      | .*v=        # or /watch\?v=
      )             # End path alternatives.
    )               # End host alternatives.
    ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
    ($|&).*         # if additional parameters are also in query string after video id.
    $%x'
    ;
    $video_linkw = '';
    $youtube_id = '';
    $result = preg_match($pattern, $url, $matches);
    if (false !== $result) {
        if (isset($matches[1]))
            $video_link = $matches[1];
        else
            $video_link = $url;
    }

    if (strpos($video_link, 'iframe')) {
        $youtube_id = 'iframe';
        $expload = explode("embed/", $video_link);
        $expload1 = $expload[1];
        $expload2 = explode('"', $expload1);
        $youtube_id = $expload2[0];
    } else {
        $youtube_id = str_replace('http://www.youtube.com/watch?v=', "", $video_link);
    }

    $youtube_id = trim($youtube_id);
    $remove = explode('&', $youtube_id);
    $youtube_id = $remove[0];

    return trim($youtube_id);
}

function media_player($file_path) {
    $html = '';

    $html .= ' <audio controls >
                    <source src="' . $file_path . '" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>';

    return $html;
}

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' kB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
