<?php

define("FLASH_DATA_KEY", "__flashData");

function ensureFlashDataContainer()
{
    if (!isset($_SESSION[FLASH_DATA_KEY])) {
        $_SESSION[FLASH_DATA_KEY] = [
            "new" => [],
            "old" => [],
        ];
    }
}

function markFlashData(string $key)
{
    ensureFlashDataContainer();
    $_SESSION[FLASH_DATA_KEY]["new"][] = $key;
}

function setFlashData(string $key, $value)
{
    $_SESSION[$key] = $value;
    markFlashData($key);
}

function trackFlashData()
{
    if (isset($_SESSION[FLASH_DATA_KEY])) {
        $_SESSION[FLASH_DATA_KEY]["old"] = $_SESSION[FLASH_DATA_KEY]["new"];
        $_SESSION[FLASH_DATA_KEY]["new"] = [];
    }
}

function deleteOldFlashData()
{
    if (isset($_SESSION[FLASH_DATA_KEY])) {
        foreach ($_SESSION[FLASH_DATA_KEY]["old"] as $oldKey) {
            unset($_SESSION[$oldKey]);
        }
        $_SESSION[FLASH_DATA_KEY]["old"] = [];
    }
}
