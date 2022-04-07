<?php

declare(strict_types=1);

namespace App\Services;

interface IPriceService
{
    public function getPrice($art): array;
}

/*
 * art
 * brand
 * price
 * delivery
 * self_store
 * qty
 * name
 *
*/
