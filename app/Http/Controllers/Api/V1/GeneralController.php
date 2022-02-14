<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ExchangeDirection;
use App\Models\RateDashboardEntity;
use App\Services\ExchangeService\DirectionsRepresenter;
use Illuminate\Http\JsonResponse;

class GeneralController extends Controller
{
    /**
     * Get service data.
     *
     * @return JsonResponse
     */
    public function getServiceData(): JsonResponse
    {
        $dashboardItems = RateDashboardEntity::visible()->with('entity')->get();

        $directionRepresenter = new DirectionsRepresenter(ExchangeDirection::getAllEnabled());

        return response()->json([
            'rate_dashboard_items' => $dashboardItems->map(function (RateDashboardEntity $item) {
                return [
                    'id' => $item->id,
                    'card_color' => $item->card_color_type,
                    'entity' => [
                        'id' => $item->entity->id,
                        'name' => $item->entity->name,
                        'alias' => $item->entity->alias,
                        'icon' => $item->entity->getLinkOnIcon(),
                        'cost' => $item->entity->getCostAsString(),
                        'enabled' => $item->entity->enabled
                    ]
                ];
            }),
            'exchange_directions' => $directionRepresenter->getRepresented()
        ], options: JSON_UNESCAPED_UNICODE);
    }
}
