<?php

//echo phpinfo();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


include_once '../config.php';
fm_mkdir($current_directory);

$file_caption = "";
$file_description = "";
$file_type = 'image';

if (!empty($_FILES)) {
//print_r($_FILES);
    foreach ($_FILES as $key => $value) {
        if (!empty($value["name"]) && $value["tmp_name"]) {
            $file_info = upload_file($value, SFM_DIRECTORY_PATH, $allowed_extensions, $sfm_document_extension);
//print_r($file_info); exit; 

            if (IS_DATABASE_INCLUDED) {
                $data = array(
                    "file_original_name" => $file_info->name,
                    "file_name" => $file_info->file_new_name,
                    "type" => $file_type,
                    "extension" => $file_info->extension,
                    "title" => str_replace("." . $file_info->extension, "", $file_info->name),
                    "caption" => $file_caption,
                    "description" => $file_description,
                    "file_path" => str_replace(SFM_SERVER_ROOT . SFM_BASE_URI, "", $file_info->path),
                    'user_id' => 1,
                    'created' => date("Y-m-d H:i:s"),
                    'updated' => date("Y-m-d H:i:s"),
                );
                $id = $db->insert('smf_files', $data);
                $file_info->id = $id;
            }
//print_r($file_info);
            $response[] = $file_info;
        }
    }
}
echo json_encode($response);

function upload_file($file, $output_dir, $ua_image_extension = [], $sfm_document_extension = []) {
//    echo  $output_dir.' - root: '.SFM_SERVER_ROOT; exit;
//    exit;

    $temp_name = $file["tmp_name"];
    $fileName = $file["name"];
    $details = array();
    $details['name'] = $fileName;
//$extension_info = get_extension($output_dir, $fileName, $ua_image_extension);

    $extension_info = pathinfo($fileName);

//        print_r($extension_info);
    $details['extension'] = $extension = $extension_info['extension'];
    $details['filename'] = $filename = $extension_info['filename'] . '_' . generate_code(5, true, 's');

//    $details['file_new_name'] = $file_new_name = generate_code(30, true, FILE_NAME_PREFIX) . '.' . $extension;
    $details['file_new_name'] = $file_new_name = generate_alias($filename . '.' . $extension);

// upload in temp folder 
//    move_uploaded_file($temp_name, $output_dir . $file_new_name);

    if (in_array($extension, $sfm_document_extension)) {
     
         $details['path'] = $output_dir . $filename; 
    } else {
        $target_file = $output_dir . $file_new_name;
        $new_width = 1200;
        $new_height = 'auto';

        resize($temp_name, $target_file, $extension, $new_width, $new_height);

// webconvert 
        $destination = $output_dir . generate_alias($filename . '.webp');
        if ($extension == "webp" || $extension == "png" || $extension == "gif") {
            //no need to convert to webp
        } else {
            convertImageToWebP($target_file, $destination);
            @unlink($target_file);
        }

        $details['path'] = $destination;
    }

//    $details['fileicon'] = $extension_info['fileicon'];
    return (object) $details;
}

function resize($temp_name, $target_file, $fileExtension, $new_width = 1200, $new_height = 300) {
// $image is the uploaded image

    list($width, $height) = getimagesize($temp_name);

//setup the new size of the image
    if ($new_height == 'auto') {
        $percentChange = $new_width / $width;
        $new_height = round(( $percentChange * $height));
    }
//    echo $new_width, $new_height; exit;

    if ($width < $new_width && $height < $new_height) {
        $new_width = $width;
        $new_height = $height;
    } else {
        if ($width < $new_width) {
            $new_width = $width;
//$new_height = $height;
        }
        if ($height < $new_height) {
//$new_width = $width;
            $new_height = $height;
        }
    }

//$ratio = $width / $height;
//$new_height = 500;
//move the file in the new location
    move_uploaded_file($temp_name, $target_file);

// resample the image       
    $new_image = imagecreatetruecolor($new_width, $new_height);

    $extension = strtolower($fileExtension);
    if ($extension == 'jpg' || $extension == 'jpeg') {
        $old_image = imagecreatefromjpeg($target_file);
        imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($new_image, $target_file, 100);
    } elseif ($extension == 'png') {
//        $old_image = imagecreatefrompng($target_file);
//        imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
//        imagepng($new_image, $target_file, 9);


        $newImg = imagecreatetruecolor($new_width, $new_height);
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $new_width, $new_height, $transparent);
        $src_w = imagesx($old_image);
        $src_h = imagesy($old_image);
        imagecopyresampled($newImg, $old_image, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
    } elseif ($extension == 'gif') {
        $old_image = imagecreatefromgif($target_file);
        imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagegif($new_image, $target_file);
    } elseif ($extension == 'webp') {
        $old_image = imagecreatefromwebp($target_file);
        imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagewebp($new_image, $target_file);
    }
}

function _save_info($file_info, $id) {
//    $data = array(
//                "file_original_name" => $file_info->name,
//                "file_name" => $file_info->file_new_name,
//                "type" => $file_type,
//                "extension" => $file_info->extension,
//                "title" => str_replace("." . $file_info->extension, "", $file_info->name),
//                "caption" => $file_caption,
//                "description" => $file_description,
//                "file_path" => $fdrive_path,
//                'user_id' => 1,
//                'created' => date("Y-m-d H:i:s"),
//                'updated' => date("Y-m-d H:i:s"),
//            );
//                $id = $db->insert('files', $data);
//                $file_info->id = $id;
}

function convertImageToWebP($source, $destination, $quality = 80) {
    $extension = pathinfo($source, PATHINFO_EXTENSION);
    if ($extension == 'jpeg' || $extension == 'jpg')
        $image = imagecreatefromjpeg($source);
    elseif ($extension == 'gif')
        $image = imagecreatefromgif($source);
    elseif ($extension == 'png')
        $image = imagecreatefrompng($source);
    elseif ($extension == 'webp')
        $image = imagecreatefromwebp($source);

//imagedestroy($source);
    return imagewebp($image, $destination, $quality);
}
?>


