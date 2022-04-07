<?php

namespace App\Services;

class DefaultStrategy implements IPriceService
{
    public function getPrice($art): array
    {
        return ['Wrong Supplier'];
    }
}
