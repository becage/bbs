<?php

namespace App\Handlers;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Image;
use GuzzleHttp\Client;

class WechatImageMsgHandler implements EventHandlerInterface
{
    public function handle($payload = null)
    {
        return 'WechatImageMsgHandler';
    }
}
