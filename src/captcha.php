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
    $image = imageFromString($string, $fontSize, 5, 5);
    for ($i = 0; $i < $obscurityLines; $i++) {
        drawCrossLine($image);
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
    global $CAPTCHA_MESSAGES;
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
        $result["errors"][] = $CAPTCHA_MESSAGES["MISSING"];
    }
    if ($formCaptcha && $formCaptcha != $sessionCaptcha) {
        $result["match"] = false;
        $result["errors"][] = $CAPTCHA_MESSAGES["WRONG"];
    }
    return $result;
}

$CAPTCHA_MESSAGES = [
    "WRONG"   => "CAPTCHA is wrong.",
    "MISSING" => "CAPTCHA is missing.",
];
