<?php

namespace Akinuri\Captcha;

class CaptchaGuard
{
    public static function remember(Captcha $captcha)
    {
        $_SESSION["captcha"] = $captcha->getString();
    }

    public static function check(): CaptchaCheck
    {
        return new CaptchaCheck(
            $_SESSION["captcha"] ?? null,
            $_POST["captcha"] ?? null,
        );
    }
}
