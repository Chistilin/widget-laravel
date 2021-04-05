<?php


namespace Widget;


use Illuminate\Support\Str;

class DisplayService
{
    /**
     * @param int|null $user_id
     * @param string|null $token
     * @param int $type
     * @return false|string|null
     */
    public function getWidgetDisplay(?int $user_id, ?string $token, int $type = 1){

        if(!$user_id) {
            session(['user_id' => mt_rand(100, 1000)]);
        }

        $widgetDeals = new ClientService();

        if (!$token) {
            session(['token' => Str::random(40)]);
            $widgetDeals->setToken(session('token'));
        }
        else{
            session(['token' => $token]);
            $widgetDeals->setToken(session('token'));
        }

        $resultWidget = null;
        $widgetDealsHtml = null;

        $widgetDeals->setUserId(session('user_id'));
        $widgetDeals->setUserIp($_SERVER['REMOTE_ADDR']);
        if (session('token')) {
            session(['token' => $widgetDeals->getToken()]);
        }


        $resultWidget = $widgetDeals->getWidgetReference($type)->getResult();
        $data['widget_deals_url'] = $resultWidget['url'] ?? null;


        if($data['widget_deals_url']) {
            $widgetDealsHtml = iconv('utf-8', 'utf-8', file_get_contents($data['widget_deals_url']));
        }

        return $widgetDealsHtml;
    }

}
