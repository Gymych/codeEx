<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GetPriceRequest;
use App\Models\SupReq;
use App\Services\GetPriceManager;

class SupReqsController extends Controller
{
    public function __invoke(GetPriceRequest $input, GetPriceManager $manager): array
    {
        $input = $input->validated();
        $storeRequest = SupReq::create(['art' => $input['art']]);
        $data = $manager->getPrice($input["art"], $input["supID"], $storeRequest->id);
        return $data;
    }
}
