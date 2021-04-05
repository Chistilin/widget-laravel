<?php

namespace Widget;

use Widget\ClientService;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\Response;

class WidgetService
{
    public function get(
        ClientService $widgetDeals,
        string $code
    ):JsonResponse
    {
        $result = $widgetDeals->setCode($code)
            ->clickPromo()
            ->getResult();

        if(!$result['restOfTimeInMinutes']){
            return new JsonResponse(
                [
                    'status' => 'fail',
                    'message' => 'Не получен параметр restOfTimeInMinutes'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(
                $result,
                $widgetDeals->getResponseCode()
            );
    }

    public function prolongation(
        ClientService $widgetDeals,
        string $code
    ):JsonResponse
    {
        $result = $widgetDeals->setCode($code)
            ->prolongationPromo()
            ->getResult();

        return new JsonResponse(
            $result,
            $widgetDeals->getResponseCode()
        );
    }

    public function use(
        ClientService $widgetDeals,
        string $code
    ):JsonResponse
    {
        $result = $widgetDeals->setCode($code)
            ->usePromo()
            ->getResult();

        return new JsonResponse(
            $result,
            $widgetDeals->getResponseCode()
        );
    }
}
