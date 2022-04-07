<?php

declare(strict_types=1);

namespace App\Services;

class DataHandlerService
{
    private const EXISTS = 1;
    private const SELF_EXISTS = 2;

    public function handle($data): array
    {
        usort($data, function ($a, $b) {
            if ($a["price"] < $b["price"]) {
                return -1;
            } else {
                return 1;
            }
        });

        $brands = [];

        $resArray = [];
        if (!is_array($data[0])) {
            return [];
        }
        foreach ($data as $item) {
            if ((!array_key_exists($item["brand"], $brands)) || ($brands[$item["brand"]]) == self::EXISTS) {
                if (array_key_exists("self_store", $item) and $item["self_store"] == self::EXISTS) {
                    $brands[$item["brand"]] = self::SELF_EXISTS;
                } else {
                    $brands[$item["brand"]] = self::EXISTS;
                }
                $resArray[] = $item;
            }
        }
        return $resArray;
    }
}
