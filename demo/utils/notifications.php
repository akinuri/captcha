<?php

require_once __DIR__ . "/flash-data.php";

function getNotifications(string $sessionKey = "notifications")
{
    return $_SESSION[$sessionKey] ?? [];
}

function addNotification(string $text, string $sessionKey = "notifications")
{
    $notifications = getNotifications($sessionKey);
    $notifications[] = $text;
    setFlashData("notifications", $notifications);
}

function addNotifications(array $texts, string $sessionKey = "notifications")
{
    foreach ($texts as $text) {
        addNotification($text, $sessionKey);
    }
}

function buildNotification($text)
{
    return "<p class=\"px-2 py-1 bg-blue-100 text-blue-900 rounded\">{$text}</p>";
}

function printNotifications($notifications, $class = "")
{
    if (!empty($notifications)) {
        echo "<div class=\"flex flex-col gap-3 {$class}\">";
        foreach ($notifications as $text) {
            echo buildNotification($text);
        }
        echo "</div>";
    }
}
