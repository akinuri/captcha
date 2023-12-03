<?php

require_once __DIR__ . "/debug.php";
require_once __DIR__ . "/string.php";
require_once __DIR__ . "/image.php";

function setCaptcha()
{
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
