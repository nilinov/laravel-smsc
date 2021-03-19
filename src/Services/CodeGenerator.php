<?php

namespace Papalapa\Laravel\Smsc\Services;

final class CodeGenerator
{
    public function __construct(private int $size)
    {
    }

    public static function generateStatic(int $size = 6): string
    {
        return (new self($size))->generate();
    }

    public function generate(): string
    {
        try {
            $code = random_int(0, 10 ** $this->size - 1);
        } catch (\Exception $e) {
            throw $e;
        }
        return sprintf("%0{$this->size}d", $code);
    }
}
