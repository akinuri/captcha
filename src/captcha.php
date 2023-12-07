<?php

namespace Akinuri\Captcha;

class Captcha
{
    private ?string $string = null;
    private ?string $image = null;

    public function __construct(
        string|int $stringOrLength,
        int $fontSize = 16,
        int $padX = 5,
        int $padY = 5,
        int $obscurityLines = 2,
    ) {
        $this->string = $stringOrLength;
        if (is_int($stringOrLength)) {
            $this->string = Random::string($stringOrLength);
        }
        $img = Image::fromString($this->string, $fontSize, $padX, $padY);
        for ($i = 0; $i < $obscurityLines; $i++) {
            Image::drawCrossLine($img);
        }
        $img = Image::toBase64($img);
        $this->image = $img;
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}
