<?php

require_once __DIR__ . "/random.php";
require_once __DIR__ . "/image.php";

function buildCaptcha(
    string|int $string,
    int $fontSize = 16,
    int $obscurityLines = 2,
) {
    if (is_int($string)) {
        $string = randomString($string);
    }
    $image = stringImage($string, $fontSize, 5, 5);
    for ($i = 0; $i < $obscurityLines; $i++) {
        drawLine($image);
    }
    $captcha = [
        "string" => $string,
        "image"  => imageAsBase64($image),
    ];
    return $captcha;
}

function setCaptcha(array $captcha)
{
    $_SESSION["captcha"] = $captcha["string"];
}

function checkCaptcha()
{
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
