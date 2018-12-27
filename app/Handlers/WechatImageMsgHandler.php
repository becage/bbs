<?php

namespace App\Handlers;

use EasyWeChat\Factory;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Image;
use App\Handlers\ImageUploadHandler;

class WechatImageMsgHandler implements EventHandlerInterface
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
        $filename = '';
        if (isset($payload['PicUrl'])) {
            // 远程图片下载到本地
            app(ImageUploadHandler::class)->downfile($payload['PicUrl']);
        }

        $emoticon = DB::select('select id,img from emoticonpkg where id = ?', [rand(1, 451)]);
        DB::update('update emoticonpkg set used=(used + 1) where id = ?', [$emoticon[0]->id]);
        $str = $this->uploadWechatImage($emoticon[0]->img);
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
