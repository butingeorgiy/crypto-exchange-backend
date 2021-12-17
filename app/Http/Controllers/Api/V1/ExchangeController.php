<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ExchangeDirection;
use App\Services\ExchangeService\DirectionsRepresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function directions(): JsonResponse
    {
        $representer = new DirectionsRepresenter(ExchangeDirection::getAllEnabled());

        return response()->json([
            'directions' => $representer->getRepresented()
        ], options: JSON_UNESCAPED_UNICODE);
    }
}
