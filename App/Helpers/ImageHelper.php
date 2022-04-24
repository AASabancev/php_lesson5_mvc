<?php

namespace App\Helpers;

class ImageHelper
{
    const ALLOWED_TYPES = ['jpg', 'jpeg', 'png'];


    public static function resaveImage(string $fileFrom, $ext, string $fileTo)
    {
        if (!file_exists($fileFrom)) {
            return;
        }

        $to_png = false;
        switch (strtolower($ext)) {
            case "png":
                $to_png = true;
                list($bg_width, $bg_height) = getimagesize($fileFrom);
                $bg = imagecreatetruecolor($bg_width, $bg_height);
                imagesavealpha($bg, true);
                imagealphablending($bg, false);
                $src = imagecreatefrompng($fileFrom);
                break;

            case "jpg":
            case "jpeg":
                $src = imagecreatefromjpeg($fileFrom);
                break;

            case "webp":
                $src = imagecreatefromwebp($fileFrom);
                break;

            case "gif":
                $src = imagecreatefromgif($fileFrom);
                break;

            case "bmp":
                $src = imagecreatefrombmp($fileFrom);
                break;

            default:
                return; // Unsupported image type
        }

        if ($to_png) {
            imagepng($src, $fileTo);
        } else {
            imagejpeg($src, $fileTo);
        }
        imagedestroy($src);
    }

}

