<?php

namespace App\Handlers;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Image;

class WechatOtherMsgHandler implements EventHandlerInterface
{
    public function handle($payload = null)
    {
        return "您来啦：😊\r\n1.可以跟阿猫聊天；\r\n2.可以跟阿猫斗图；\r\n3.回复‘笑话’；\r\n4.回复‘天气’；\r\n5.回复‘绕口令’；\r\n👉<a href='https://aimuti.com/s'>地铁路线图</a>";
    }
}
