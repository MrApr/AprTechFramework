<?php

function convertToObject(array $array)
{
    return (object) $array;
}

function storeFiles(string $file_name)
{
    if (($_FILES[$file_name]['name'] == ""))
    {
        die("Requested uploaded file doesnt exists");
    }

    // Where the file is going to be stored
    $target_dir = "upload/";
    $file = $_FILES[$file_name]['name'];
    $path = pathinfo($file);
    $filename = $path['filename'];
    $ext = $path['extension'];
    $temp_name = $_FILES[$file_name]['tmp_name'];
    $path_filename_ext = $target_dir.$filename.time().".".$ext;
    // Check if file already exists
    if (file_exists($path_filename_ext)) {
        die("Sorry, file already exists.");
    }else{
        try{
            move_uploaded_file($temp_name,$path_filename_ext);
        }catch (Exception $e)
        {
            die($e->getMessage());
        }
        return $path_filename_ext;
    }
}

function deleteUploadedFile(string $path)
{
    if(file_exists($path))
    {
        unlink($path);
    }
}