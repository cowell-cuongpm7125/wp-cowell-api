<?php

namespace CoWell\Services;

require_once( ABSPATH . 'wp-admin/includes/file.php' );

class UploadService
{
    public function upload($name)
    {
        $targetDir = ABSPATH . 'wp-content/uploads/avatar/';

        if (isset($_FILES[$name])) {
            return $this->fileUpload($_FILES[$name], $targetDir);
        }

        if (isset($_REQUEST[$name])) {
            return $this->fileUpload($_REQUEST[$name], $targetDir);
        }

        throw new \Exception('Image must is  file format or base64 format', 422);
    }

    function base64Upload($image, $targetDir)
    {
        $images = explode(';base64,', $image);

        if (count($images) === 2) {
            $image = array_pop($images);
        }

        $imgData = base64_decode($image);

        $f = finfo_open();
        $mimeType = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);
        $typeFile = explode('/', $mimeType);
        $targetFile =  $targetDir . $this->generateFileName($typeFile[1]);

        file_put_contents($targetFile, $imgData);

        return $targetFile;
    }

    function fileUpload($file, $targetDir)
    {
        $tail = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $targetFile = $targetDir . $this->generateFileName($tail);
        move_uploaded_file($file["tmp_name"], $targetFile);

        return $targetFile;
    }

    function generateFileName($tail)
    {
        return uniqid() . '-' . time() . '.' . $tail;
    }
}
