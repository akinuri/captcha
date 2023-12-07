<?php

namespace Akinuri\Captcha;

enum CaptchaCheckMessages: string
{
    case SessionValueMissing = "Captcha is not set.";
    case FormValueEmpty = "Captcha is empty or missing.";
    case FormValueWrong = "Captcha is wrong.";
}
