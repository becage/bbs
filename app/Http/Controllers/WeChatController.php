<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Message;
use App\Handlers\WechatTextMsgHandler;
use App\Handlers\WechatImageMsgHandler;
use App\Handlers\WechatOtherMsgHandler;

use EasyWeChat\Kernel\Messages\Image;
use App\Handlers\TuringRobotHandler;
use Inimage;
use App\Handlers\ImageUploadHandler;

class WeChatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        // $lk = '笑话';
        // preg_match('/笑话+/', $lk);
        // $res = app(TuringRobotHandler::class)->chat($lk);
        // $img = app(ImageUploadHandler::class)->downfile($res['second'], 'storage/turings/joke.jpg');
        // $img = Inimage::make($img);
        // $img->insert('storage/turings/logo.jpg', 'bottom-right', 10, 10);
        // $img->text($res['first'], 202, 222, function ($font) {
        //     $font->file('fonts/chinese.ttf');
        //     $font->size('28');
        //     $font->color('#f44336');
        //     $font->align('center');
        //     $font->valign('top');
        //     $font->angle(45);
        // });
        // $img->save('storage/turings/foobar.jpg');
        // exit();
        // Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $app->server->push(WechatTextMsgHandler::class, Message::TEXT); // 文本消息
        $app->server->push(WechatImageMsgHandler::class, Message::IMAGE); // 图片消息
        $app->server->push(WechatOtherMsgHandler::class);

        return $app->server->serve();
    }
}
