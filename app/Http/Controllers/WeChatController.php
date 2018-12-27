<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Image;
use App\Handlers\WechatTextMsgHandler;
use App\Handlers\WechatImageMsgHandler;
use App\Handlers\WechatOtherMsgHandler;

class WeChatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $app->server->push(WechatTextMsgHandler::class, Message::TEXT); // 文本消息
        $app->server->push(WechatImageMsgHandler::class, Message::IMAGE); // 图片消息
        $app->server->push(WechatOtherMsgHandler::class);

        return $app->server->serve();
    }
}
