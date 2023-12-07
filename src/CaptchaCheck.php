<?php

namespace Akinuri\Captcha;

class CaptchaCheck
{
    public bool $match           = false;
    public array $messages       = [];
    public ?string $sessionValue = null;
    public ?string $formValue    = null;

    public function __construct(?string $sessionValue, ?string $formValue)
    {
        if ($sessionValue && $formValue) {
            $this->match = $formValue == $sessionValue;
            if ($this->match == false) {
                $this->messages[] = CaptchaCheckMessages::FormValueWrong->value;
            }
        } else {
            if (empty($sessionValue)) {
                $this->messages[] = CaptchaCheckMessages::SessionValueMissing->value;
            }
            if (empty($formValue)) {
                $this->messages[] = CaptchaCheckMessages::FormValueEmpty->value;
            }
        }
    }
}
