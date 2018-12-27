<?php

namespace App\Handlers;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Image;
use App\Handlers\TuringRobotHandler;
use App\Handlers\ImageUploadHandler;

class WechatTextMsgHandler implements EventHandlerInterface
{
    protected $wxconfig = [
            'app_id' => 'wx72135e88f87bd95b',
            'secret' => '2925689cabb66e17b6a146075c4a197f',
            'token' => 'sunzi',
            'aes_key' => '50fFe3p7TW2aaF7YoQLRhxvZdJ91F3KdxhE8D2jmtLX',
            'response_type' => 'array'
        ];

    public function handle($payload = null)
    {
        // easywechat收到用户发的表情，为if的判断内容
        if ($payload['Content'] == '【收到不支持的消息类型，暂无法显示】') {
            return 'emoticon';
        } else {
            $str = app(TuringRobotHandler::class)->chat($payload['Content']);

            // 正则匹配 如果是图片地址，则生成微信media返回
            if (preg_match('/^http.*\.[jpg|png|jpeg]/', $str)) {
                // 远程图片下载到本地
                $img = app(ImageUploadHandler::class)->downfile($str);
                // public/storage/turings 文件上传到微信素材 media 并返回 media_id
                $app = Factory::officialAccount($this->wxconfig);
                $media_info = $app->media->uploadImage($img);
                $str = isset($media_info['media_id']) ? new Image($media_info['media_id']) : $str;
            }
        }
        return $str;
    }
}
