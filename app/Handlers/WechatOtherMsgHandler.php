<?php

namespace App\Handlers;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Image;
use GuzzleHttp\Client;

class WechatOtherMsgHandler implements EventHandlerInterface
{
    public function handle($payload = null)
    {
        return 'WechatOtherMsgHandler';
    }
}
