<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TissStrategy implements IPriceService
{
    private const IS_MAIN_WAREHOUSE = 0;

    public function getPrice($art): array
    {
        $res = [];
        $data = $this->getAllPrices($art);
        if (count($data)) {
            foreach ($data as $item) {
                foreach ($item['warehouse_offers'] as $i => $offer) {
                    $item['warehouse_offers'][$i]['art'] = $item['article'];
                    $item['warehouse_offers'][$i]['brand'] = $item['brand'];
                    $item['warehouse_offers'][$i]['name'] = $item['article_name'];
                    $item['warehouse_offers'][$i]['delivery'] = $offer['delivery_period'];
                    $item['warehouse_offers'][$i]['qty'] = $offer['quantity'];
                    if ($offer['is_main_warehouse']) {
                        $item['warehouse_offers'][$i]['self_store'] = 1;
                    }
                }
                $res += $item['warehouse_offers'];
            }
        }
        return $res;
    }

    private function getAllPrices($art, $brand = ''): array
    {
        $params = array('JSONparameter' => "{
            'Brand_Article_List': [{
                'Brand': '" . $brand . "',
                'Article': '" . $art . "'
            }],
            'is_main_warehouse': " . self::IS_MAIN_WAREHOUSE .
        "}");

        return Http::withToken(Config('suppliers.tiss.token'))->get(
            Config('suppliers.tiss.url') . 'StockByArticleList',
            $params
        )->json();
    }
}
