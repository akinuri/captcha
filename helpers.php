<?php

function print_r2($value) {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
}

function redirect(string $url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit;
}


#region ==================== FLASH DATA

define("FLASH_DATA_KEY", "__flashData");

function ensureFlashDataContainer() {
    if (!isset($_SESSION[FLASH_DATA_KEY])) {
        $_SESSION[FLASH_DATA_KEY] = [
            "new" => [],
            "old" => [],
        ];
    }
}

function markFlashData(string $key) {
    ensureFlashDataContainer();
    $_SESSION[FLASH_DATA_KEY]["new"][] = $key;
}

function setFlashData(string $key, $value) {
    $_SESSION[$key] = $value;
    markFlashData($key);
}

function trackFlashData() {
    if (isset($_SESSION[FLASH_DATA_KEY])) {
        $_SESSION[FLASH_DATA_KEY]["old"] = $_SESSION[FLASH_DATA_KEY]["new"];
        $_SESSION[FLASH_DATA_KEY]["new"] = [];
    }
}

function deleteOldFlashData() {
    if (isset($_SESSION[FLASH_DATA_KEY])) {
        foreach ($_SESSION[FLASH_DATA_KEY]["old"] as $oldKey) {
            unset($_SESSION[$oldKey]);
        }
        $_SESSION[FLASH_DATA_KEY]["old"] = [];
    }
}

#endregion


#region ==================== NOTIFICATIONS

function getNotifications(string $sessionKey = "notifications") {
    return $_SESSION[$sessionKey] ?? [];
}

function addNotification(string $text, string $sessionKey = "notifications") {
    $notifications = getNotifications($sessionKey);
    $notifications[] = $text;
    setFlashData("notifications", $notifications);
}

function addNotifications(array $texts, string $sessionKey = "notifications") {
    foreach ($texts as $text) {
        addNotification($text, $sessionKey);
    }
}

function buildNotification($text) {
    return "<p class=\"px-2 py-1 bg-blue-100 text-blue-900 rounded\">{$text}</p>";
}

function printNotifications($notifications, $class = "") {
    if (!empty($notifications)) {
        echo "<div class=\"flex flex-col gap-2 {$class}\">";
        foreach ($notifications as $text) {
            echo buildNotification($text);
        }
        echo "</div>";
    }
}

#endregion


#region ==================== FORM DATA

define("FORM_DATA_KEY", "formData");

function saveFormData() {
    setFlashData(FORM_DATA_KEY, $_POST);
}

function getOldFormValue(string $name): string {
    return $_SESSION[FORM_DATA_KEY][$name] ?? "";
}

#endregion


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

function checkCaptcha() {
    $sessionCaptcha = $_SESSION["captcha"] ?? null;
    $formCaptcha    = $_POST["captcha"] ?? null;
    $result = [
        "match"   => true,
        "errors"  => [],
        "session" => $sessionCaptcha,
        "form"    => $formCaptcha,
    ];
    if (!$formCaptcha) {
        $result["match"] = false;
        $result["errors"][] = "CAPTCHA is missing.";
    }
    if ($formCaptcha && $formCaptcha != $sessionCaptcha) {
        $result["match"] = false;
        $result["errors"][] = "CAPTCHA is wrong.";
    }
    return $result;
}

#endregion

