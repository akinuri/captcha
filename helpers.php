<?php

function print_r2($value) {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
}


#region ==================== STRING

function randomString(
    int $length = 10,
    string $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",
): string {
    $string = [];
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; ++$i) {
        $string[] = $chars[random_int(0, $max)];
    }
    $string = implode("", $string);
    return $string;
}

#endregion


#region ==================== IMAGE

function stringImage($captchaText = "hello", $fontSize = 16): object {
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
        "arial.ttf",
        $captchaText,
    );
    drawLine($image, $imgWidth, $imgHeight);
    drawLine($image, $imgWidth, $imgHeight);
    return $image;
}

function drawLine($image, $imgWidth, $imgHeight) {
    $lineStartX = 0;
    $lineStartY = rand(0, round($imgHeight));
    $lineEndX   = $imgWidth;
    $lineEndY   = rand(0, round($imgHeight));
    $lineColor  = imagecolorallocate($image, 0, 0, 0);
    imageline($image, round($lineStartX), round($lineStartY), round($lineEndX), round($lineEndY), $lineColor);
}

function getImageResourceData(object $image): string {
    ob_start();
    imagepng($image);
    $data = ob_get_clean();
    return $data;
}

function imageAsBase64($image): string {
    if (gettype($image) == "object") {
        $image = getImageResourceData($image);
    }
    $base64 = "data:image/png;base64," . base64_encode($image);
    return $base64;
}

function showImage($image) {
    header("Content-type: image/png");
    imagepng($image);
    imagedestroy($image);
    exit;
}

#endregion


#region ==================== CAPTCHA

function setCaptcha() {
    $randomString = randomString(4);
    $stringImage = stringImage($randomString);
    $img64 = imageAsBase64($stringImage);
    $result = [
        "string" => $randomString,
        "image" => $img64,
    ];
    $_SESSION["captcha"] = $randomString;
    return $result;
}

#endregion

