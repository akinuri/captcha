<?php

function randomString(
    int $length = 10,
    string $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",
): string {
    $string = [];
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; ++$i) {
        $string[] = $chars[random_int(0, $max)];
    }
    $string = implode("", $string);
    return $string;
}
