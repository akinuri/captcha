<?php

namespace Akinuri\Captcha;

enum CaptchaCheckMessages: string
{
    case SessionValueMissing = "CAPTCHA is not set.";
    case FormValueEmpty = "CAPTCHA is empty or missing.";
    case FormValueWrong = "CAPTCHA is wrong.";
}
