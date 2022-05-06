<?php

namespace App\Models\Traits;

use App\Helpers\FileHelper;
use App\Helpers\ImageMagick;

trait ImageTrait
{
    public function deleteImage()
    {
        if (empty($this->image) || !file_exists(DOC_ROOT . $this->image)) {
            return;
        }

        unlink(DOC_ROOT . $this->image);
    }

    public function uploadImage(?array $image, $withWatermark = true)
    {
        if (empty($image) || empty($image['name'])) {
            return;
        }

        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $path = DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
            $filename = FileHelper::generatestring(15) . '.' . $ext;
            $filepath = $path . $filename;

            $image = new ImageMagick($image['tmp_name'], $ext);
            $image->resizeWidthMoreHeight(200, 200);
            if ($withWatermark) {
                $watermark = DOC_ROOT . '/files/watermark.png';
                if (!empty($watermark)) {
                    $image->watermark($watermark);
                }
            }
            $image->saveToPath(DOC_ROOT . $filepath);

            // костыль для винды :(

            $this->deleteImage();
            $this->image = str_replace('\\', '/', $filepath);
        }
    }
}