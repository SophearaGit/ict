<?php

namespace App\Helpers;

use Spatie\Image\Image;

class KhmerPdfText
{
    public static function draw(
        $pdf,
        string $text,
        float $x,
        float $y,
        float $w = 80,
        float $h = 15,
        int $fontSize = 32,
        string $align = 'left'
    ) {
        // ✅ UTF-8 safe
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        $hash = md5($text . $fontSize . $align);
        $svgPath = storage_path("app/tmp_{$hash}.svg");
        $pngPath = storage_path("app/tmp_{$hash}.png");

        if (!file_exists($pngPath)) {

            // 📦 Load font and encode to base64
            $fontFile = public_path('fonts/Battambang/Battambang-Regular.ttf');
            $fontData = base64_encode(file_get_contents($fontFile));

            // 🎯 Alignment
            $textAnchor = match ($align) {
                'center' => 'middle',
                'right' => 'end',
                default => 'start'
            };

            $xPos = match ($align) {
                'center' => '50%',
                'right' => '95%',
                default => '5%'
            };

            // 🧾 SVG with embedded font
            $svg = '
            <svg width="1200" height="200" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <style>
                        @font-face {
                            font-family: "Battambang";
                            src: url("data:font/truetype;charset=utf-8;base64,' . $fontData . '") format("truetype");
                        }

                        text {
                            font-family: "Battambang";
                            font-size: ' . $fontSize . 'px;
                            fill: black;
                        }
                    </style>
                </defs>

                <text x="' . $xPos . '" y="50%" dominant-baseline="middle" text-anchor="' . $textAnchor . '">
                    ' . htmlspecialchars($text) . '
                </text>
            </svg>';

            file_put_contents($svgPath, $svg);

            // 🔄 Convert SVG → PNG
            Image::load($svgPath)
                ->format('png')
                ->save($pngPath);
        }

        // 📌 Place into PDF
        $pdf->Image($pngPath, $x, $y, $w, $h, 'PNG');
    }
}
