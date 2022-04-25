<?php

namespace App\Helpers;

class FileHelper
{

    protected $basename;
    protected $extension;
    protected $mime_type;
    protected $file_path;
    protected $filesize;
    protected $is_uploaded;

    public function __construct($data)
    {
        $this->is_uploaded = false;
        if (is_string($data)) {
            if (is_file($data)) {
                $this->constructFromPath($data);
            }
        } elseif (is_array($data)) {
            if (is_file($data['tmp_name'])) {
                $this->constructFromTemp($data);
            }
        } elseif (is_resource($data)) {
            $this->constructFromResource($data);
        } else {
            trigger_error('Не удалось определить файл', E_USER_ERROR);
        }
    }


    protected function constructFromPath($file_path)
    {
        $this->file_path = $file_path;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $this->mime_type = finfo_file($finfo, $this->file_path);
        finfo_close($finfo);

        $this->updateFileData($file_path);
    }

    protected function constructFromTemp(array $data_file)
    {
        $this->constructFromPath($data_file['tmp_name']);
        $this->updateFileData($data_file['name'], $data_file['tmp_name']);

        $this->is_uploaded = true;
    }

    protected function constructFromResource($data)
    {
        // todo
    }

    protected function updateFileData($filename, $file_path = null)
    {
        if(empty($file_path)){
            $file_path = $filename;
        }
        $this->extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $this->basename = basename($filename, '.'.$this->extension);
        $this->filesize = filesize($file_path);
    }


    public function __get($key)
    {
        switch ($key) {
            case "basename":
                return $this->getBasename();
                break;
            case "extension":
                return $this->getExtension();
                break;
            // Далее, под сомнением!!
            case "filename":
                return $this->getFileName();
                break;
        }

        return $this->$key;
    }

    public function getBasename()
    {
        return $this->basename;
    }

    public function getExtension()
    {
        if (!empty($this->extension)) {
            return $this->extension;
        }

        return false;
    }

    public function getFileName()
    {
        if ($this->getExtension()) {
            return $this->getBasename().".".$this->getExtension();
        }

        return $this->getBasename();
    }

    public function getFullPath($root = "")
    {
        return $this->file_path;
    }

    public function getFileSize()
    {
        return $this->filesize;
    }


    //генерирует строку из набора случайных символов
    public static function generatestring($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }
}
