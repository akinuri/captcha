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
function stringImage($captchaText = "hello", $fontSize = 16): object
{
    $imgPadX    = $fontSize / 3;
    $imgPadY    = $imgPadX / 3;
    $lineHeight = 1.7;
    $maxCharWidth = $fontSize * 1.1;
    $imgWidth   = strlen($captchaText) * $maxCharWidth + $imgPadX * 2;
    $imgHeight  = $fontSize * $lineHeight + $imgPadY * 2;
    $image      = imagecreatetruecolor(round($imgWidth), round($imgHeight));
    $bgColor    = imagecolorallocate($image, 255, 255, 255);
    $textColor  = imagecolorallocate($image, 0, 0, 0);
    $textY      = $imgPadY + $fontSize + (($lineHeight - 1) * $fontSize / 2);
    imagefilledrectangle($image, 0, 0, round($imgWidth), round($imgHeight), $bgColor);
    imagettftext(
        $image,
        $fontSize,
        0,
        round($imgPadX),
        round($textY),
        $textColor,
        __DIR__ . "/arial.ttf",
        $captchaText,
    );
    drawLine($image, $imgWidth, $imgHeight);
    drawLine($image, $imgWidth, $imgHeight);
    return $image;
}

function drawLine($image, $imgWidth, $imgHeight)
{
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
