<?php

namespace App\Handlers;

use DB;
use Log;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Image;
use App\Handlers\TuringRobotHandler;
use App\Handlers\ImageUploadHandler;
use Inimage;

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
            $emoticon = DB::select('select id,img from emoticonpkg where id = ?', [rand(1, 627)]);
            DB::update('update emoticonpkg set used=(used + 1) where id = ?', [$emoticon[0]->id]);
            $str = $this->uploadWechatImage($emoticon[0]->img);
        } else {
            // 图灵机器人 api 接口
            $response = app(TuringRobotHandler::class)->chat($payload['Content']);
            $str = $response['first'];
            if (preg_match('/笑话+/', $payload['Content'])) {
                Log::info('1.');
                $img = app(ImageUploadHandler::class)->downfile($response['second'], 'storage/turings/joke.jpg');
                Log::info('2.');
                $img = Inimage::make($img);
                $img->insert('storage/turings/logo.jpg', 'bottom-right', 10, 10);
                $img->text($response['first'], 202, 222, function ($font) {
                    $font->file('fonts/chinese.ttf');
                    $font->size('28');
                    $font->color('#f44336');
                    $font->align('center');
                    $font->valign('top');
                    $font->angle(45);
                });
                $img->save('storage/turings/foobar.jpg');
                // public/storage/turings 文件上传到微信素材 media 并返回 media_id
                $str = $this->uploadWechatImage('storage/turings/foobar.jpg');
            } else {
                // 正则匹配 如果是图片地址，则生成微信media返回
                if (preg_match('/^http.*\.[jpg|png|jpeg]/', $response['first'])) {
                    // 远程图片下载到本地
                    $img = app(ImageUploadHandler::class)->downfile($response['first']);
                    // public/storage/turings 文件上传到微信素材 media 并返回 media_id
                    $str = $this->uploadWechatImage($img);
                }    
            }
        }
        return $str;
    }

    // 上传到微信素材 media 并返回 media_id
    public function uploadWechatImage($img)
    {
        $wxapp = Factory::officialAccount($this->wxconfig);
        $media_info = $wxapp->media->uploadImage($img);
        return isset($media_info['media_id']) ? new Image($media_info['media_id']) : '再发一次呗～';
    }
}
