<?php

namespace App\Service;

class CodeCreator
{
    public function createCode(string $prefix): string
    {
        return $prefix . '-' . random_int(10000, 99999);
    }
}