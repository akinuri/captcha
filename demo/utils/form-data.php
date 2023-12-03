<?php

require_once __DIR__ . "/flash-data.php";

define("FORM_DATA_KEY", "formData");

function saveFormData()
{
    setFlashData(FORM_DATA_KEY, $_POST);
}

function getOldFormValue(string $name): string
{
    return $_SESSION[FORM_DATA_KEY][$name] ?? "";
}
