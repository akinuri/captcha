<?php

function redirect(string $url, $statusCode = 302)
{
    header('Location: ' . $url, true, $statusCode);
    exit;
}

function baseURL(string $suffix = null): string
{
    $url = sprintf(
        "%s://%s",
        "http",
        $_SERVER["HTTP_HOST"],
    );
    if ($suffix) {
        if (!str_ends_with($url, "/") && !str_starts_with($suffix, "/")) {
            $url .= "/";
        }
        $url .= $suffix;
    }
    return $url;
}
