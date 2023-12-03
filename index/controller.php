<?php
defined("PARENT") OR exit("Direct script access is not allowed.");
require "utils/debug.php";
require "utils/url.php";
require "utils/string.php";
require "utils/flash-data.php";
require "utils/notifications.php";
require "utils/form-data.php";
require "utils/image.php";
require "utils/captcha.php";
session_start();
deleteOldFlashData();
trackFlashData();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $captcha = setCaptcha();
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $value) {
        if (is_string($value)) {
            $value = trim($value);
            if ($value === "") {
                $value = null;
            }
            $_POST[$key] = $value;
        }
    }
    $captchaResult = checkCaptcha();
    $errors = [];
    $name = $_POST["name"] ?? null;
    if (empty($name)) {
        $errors[] = "Name field must not be empty.";
    }
    if (strlen($name) < 2 || strlen($name) > 10) {
        $errors[] = "Name field length must be in the range of 2-10.";
    }
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