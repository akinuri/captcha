<?php

require_once __DIR__ . "/random.php";
require_once __DIR__ . "/image.php";

function buildCaptcha(string|int $string)
{
    if (is_int($string)) {
        $string = randomString($string);
    }
    $captcha = [
        "string" => $string,
        "image"  => imageAsBase64(stringImage($string)),
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
