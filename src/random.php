<?php

function pickRandomItems(array $items, int $count = 1): array
{
    $itemsCount = count($items);
    $maxIndex = $itemsCount - 1;
    $picks = [];
    for ($i = 0; $i < $count; ++$i) {
        $picks[] = $items[random_int(0, $maxIndex)];
    }
    return $picks;
}

function randomString(
    int $length = 10,
    string $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",
): string {
    $picks = pickRandomItems(str_split($chars, 1), $length);
    $picks = implode("", $picks);
    return $picks;
}
