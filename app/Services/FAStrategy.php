<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FAStrategy implements IPriceService
{
    public function getPrice($art): array
    {
        $data = Http::get(Config('suppliers.fa.url') . 'listGoods', [
                'login' => Config('suppliers.fa.login'),
                'pass' => Config('suppliers.fa.pass'),
                'art' => $art
            ])->json();

        for ($i = 0; $i < count($data); $i++) {
            if (in_array($data[$i]["whse"], ["MSK","VRN"])) {
                $data[$i]["self_store"] = 1;
            }
            $data[$i]["delivery"] = $data[$i]["d_deliv"];
            $data[$i]["qty"] = $data[$i]["num"];
        }

        return $data;
    }
}
