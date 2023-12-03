<?php

require_once __DIR__ . "/flash-data.php";

define("FORM_DATA_KEY", "formData");

function normalizeFormData()
{
    foreach ($_POST as $key => $value) {
        if (is_string($value)) {
            $value = trim($value);
            if ($value === "") {
                $value = null;
            }
            $_POST[$key] = $value;
        }
    }
}

function saveFormData()
{
    setFlashData(FORM_DATA_KEY, $_POST);
}

function getOldFormValue(string $name): string
{
    return $_SESSION[FORM_DATA_KEY][$name] ?? "";
}
