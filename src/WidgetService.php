<?php

namespace Widget;

use App\Component\Discount\Model\Discount;
use Widget\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class WidgetService
{
    private $session;

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em = $em;
    }

    /**
     * @param \App\Services\Widget\ClientService $widgetDeals
     * @param string $code
     * @return JsonResponse
     */
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

        $coupon = $this->em->getRepository(Discount::class)
            ->findOneBy([
                'code' => $code
            ]);

        if (empty($coupon)) {
            $discount = new Discount();
            $discount->setCode($result['code']);
            $discount->setName('widget'.$result['code']);
            $discount->setDiscount($result['discount']);
            $this->em->persist($discount);
            $this->em->flush();
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
