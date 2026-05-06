<?php

namespace App\Helpers;

class KhmerImageText
{
    public static function render($text, $fontSize = 28)
    {
        $font = public_path('fonts/Battambang/Battambang-Regular.ttf');

        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        // 🖼 canvas
        $width = 1200;
        $height = 200;

        $img = imagecreatetruecolor($width, $height);

        // transparent background
        imagesavealpha($img, true);
        $trans = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $trans);

        $black = imagecolorallocate($img, 0, 0, 0);

        // center text
        $bbox = imagettfbbox($fontSize, 0, $font, $text);
        $textWidth = $bbox[2] - $bbox[0];

        $x = ($width - $textWidth) / 2;
        $y = 110;

        // ✍️ draw text
        imagettftext($img, $fontSize, 0, $x, $y, $black, $font, $text);

        // save temp file
        $file = storage_path('app/tmp_' . uniqid() . '.png');
        imagepng($img, $file);
        imagedestroy($img);

        return $file;
    }
}
