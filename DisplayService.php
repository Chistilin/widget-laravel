<?php


namespace App\Services\Widget;

use App\Services\Widget\ClientService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DisplayService
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param int|null $user_id
     * @param string|null $token
     * @param int $type
     * @return false|string|null
     */
    public function getWidgetDisplay(?int $user_id, ?string $token, int $type = 1){

        if(!$user_id) {
            $this->session->set('user_id', random_int(100, 1000));
        }

        $widgetDeals = new ClientService();

        if (!$token) {
            $this->session->set('token', $this->random_str(32));
            $widgetDeals->setToken($this->session->get('token'));
        }
        else{
            $this->session->set('token', $token);
            $widgetDeals->setToken($this->session->get('token'));
        }

        $resultWidget = null;
        $widgetDealsHtml = null;

        $widgetDeals->setUserId($this->session->get('user_id'));
        $widgetDeals->setUserIp($_SERVER['REMOTE_ADDR']);
        if ($this->session->get('token')) {
            $this->session->set('token', $widgetDeals->getToken());
        }


        $resultWidget = $widgetDeals->getWidgetReference($type)->getResult();
        $data['widget_deals_url'] = $resultWidget['url'] ?? null;


        if($data['widget_deals_url']) {
            $widgetDealsHtml = iconv('utf-8', 'utf-8', file_get_contents($data['widget_deals_url']));
        }

        return $widgetDealsHtml;
    }

    /**
     * @param int $length
     * @param string $keyspace
     * @return string
     * @throws \Exception
     */
    private function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

}
