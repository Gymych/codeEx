<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Supplier;
use App\Models\SupResp;

class GetPriceManager
{
    private DataHandlerService $dataHandler;

    public function __construct(DataHandlerService $dataHandler)
    {
        $this->dataHandler = $dataHandler;
    }

    public function getPrice($art, $supID, $reqID): array
    {
        $strategy = $this->getStrategy($supID);
        $data = $strategy->getPrice($art);
        $data = $this->handleData($data);
        $this->storeResponse($data, $reqID, $supID);
        return $data;
    }

    private function storeResponse($data, $reqID, $supID): void
    {
        SupResp::create([
            'data' => json_encode($data),
            'sup_req_id' => $reqID,
            'supplier_id' => $supID
        ]);
    }
    private function getStrategy($supID): IPriceService
    {
        $supplier = Supplier::find($supID);
        $supName = $supplier->shortName;
        $strategyName = $supName . "Strategy";
        $strategyClass = __NAMESPACE__ . "\\" . $strategyName;
        if (!class_exists($strategyClass)) {
            return new DefaultStrategy();
        }
        return new($strategyClass);
    }

    private function handleData($data): array
    {
        return $this->dataHandler->handle($data);
    }
}
