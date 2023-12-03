<?php
defined("PARENT") or exit("Direct script access is not allowed.");

require __DIR__ . "/../utils/debug.php";
require __DIR__ . "/../utils/url.php";
require __DIR__ . "/../utils/flash-data.php";
require __DIR__ . "/../utils/notifications.php";
require __DIR__ . "/../utils/form-data.php";
require __DIR__ . "/../../src/captcha.php";

session_start();

deleteOldFlashData();
trackFlashData();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // $captcha = buildCaptcha("foobar");
    $captcha = buildCaptcha(5);
    setCaptcha($captcha);
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    normalizeFormData();

    $errors = [];

    $name = $_POST["name"] ?? null;
    if (empty($name)) {
        $errors[] = "Name field must not be empty.";
    }
    if (strlen($name) < 2 || strlen($name) > 10) {
        $errors[] = "Name field length must be in the range of 2-10.";
    }

    $captchaResult = checkCaptcha();
    if ($captchaResult["match"] == false) {
        $errors = array_merge($errors, $captchaResult["errors"]);
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
