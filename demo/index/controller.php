<?php

require __DIR__ . "/../../vendor/autoload.php";

use Akinuri\Captcha\Captcha;
use Akinuri\Captcha\CaptchaGuard;

defined("PARENT") or exit("Direct script access is not allowed.");

require __DIR__ . "/../utils/debug.php";
require __DIR__ . "/../utils/url.php";
require __DIR__ . "/../utils/flash-data.php";
require __DIR__ . "/../utils/notifications.php";
require __DIR__ . "/../utils/form-data.php";

session_start();

deleteOldFlashData();
trackFlashData();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $captcha = new Captcha(5, 20);
    CaptchaGuard::remember($captcha);
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    normalizeFormData();

    $errors = [];

    $name = $_POST["name"] ?? null;
    if (empty($name)) {
        $errors[] = "Name field must not be empty.";
    }
    if (!empty($name) && (strlen($name) < 2 || strlen($name) > 10)) {
        $errors[] = "Name value length must be in the range of 2-10.";
    }

    $captchaCheck = CaptchaGuard::check();
    if ($captchaCheck->match == false) {
        $errors = array_merge($errors, $captchaCheck->messages);
    }

    if ($errors) {
        saveFormData();
        addNotifications($errors);
        redirect(baseURL());
    }

    // process form data

    addNotification("The form is submitted.");

    redirect(baseURL());
}
