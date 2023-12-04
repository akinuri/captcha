<?php

function imagettfbbox2(
    float $size,
    float $angle,
    string $font_filename,
    string $string,
    array $options = [],
): array|false {
    $box = imagettfbbox($size, $angle, $font_filename, $string, $options);
    if (is_array($box)) {
        $box = [
            "upper-left"  => ["x" => $box[6], "y" => $box[7]],
            "upper-right" => ["x" => $box[4], "y" => $box[5]],
            "lower-right" => ["x" => $box[2], "y" => $box[3]],
            "lower-left"  => ["x" => $box[0], "y" => $box[1]],
        ];
        $box["width"]  = $box["upper-right"]["x"] - $box["upper-left"]["x"];
        $box["height"] = abs($box["upper-right"]["y"] - $box["lower-left"]["y"]);
    }
    return $box;
}

// https://en.wikipedia.org/wiki/CAPTCHA
function stringImage(
    $string = "hello",
    $fontSize = 16,
    $imgPadX = 0,
    $imgPadY = 0,
): object
{
    $textBox = imagettfbbox2($fontSize, 0, __DIR__ . "/arial.ttf", $string);
    $imageMaxStringWidth = $fontSize * (strlen($string) * 0.8);
    $centerOffsetX = ($imageMaxStringWidth - $textBox["width"]) / 2;
    $centerOffsetX = round($centerOffsetX);
    $centerOffsetY = ($fontSize - $textBox["height"])  / 2;
    $centerOffsetY = round($centerOffsetY);
    $imageWidth  = $imageMaxStringWidth + $imgPadX * 2;
    $imageHeight = $fontSize + $imgPadY * 2;
    $imageWidth  = round($imageWidth);
    $imageHeight = round($imageHeight);
    $image      = imagecreatetruecolor($imageWidth, $imageHeight);
    $bgColor    = imagecolorallocate($image, 255, 255, 255);
    $textColor  = imagecolorallocate($image, 0, 0, 0);
    imagefilledrectangle($image, 0, 0, $imageWidth, $imageHeight, $bgColor);
    imagettftext(
        $image,
        $fontSize,
        0,
        $imgPadX + $centerOffsetX,
        $imgPadY + $centerOffsetY + $textBox["height"] - $textBox["lower-left"]["y"],
        $textColor,
        __DIR__ . "/arial.ttf",
        $string,
    );
    return $image;
}

function drawLine($image)
{
    $imgWidth  = imagesx($image);
    $imgHeight = imagesy($image);
    $lineStartX = 0;
    $lineStartY = rand(0, round($imgHeight));
    $lineEndX   = $imgWidth;
    $lineEndY   = rand(0, round($imgHeight));
    $lineColor  = imagecolorallocate($image, 0, 0, 0);
    imageline($image, round($lineStartX), round($lineStartY), round($lineEndX), round($lineEndY), $lineColor);
}

function getImageResourceData(object $image): string
{
    ob_start();
    imagepng($image);
    $data = ob_get_clean();
    return $data;
}

function imageAsBase64($image): string
{
    if (is_object($image)) {
        $image = getImageResourceData($image);
    }
    $base64 = "data:image/png;base64," . base64_encode($image);
    return $base64;
}

function showImage($image)
{
    header("Content-type: image/png");
    imagepng($image);
    imagedestroy($image);
    exit;
}
