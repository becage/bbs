<?php

namespace App\Handlers;

use GuzzleHttp\Client;

class TuringRobotHandler
{
    public function chat($text)
    {
        // 实例化 HTTP 客户端
        $http = new Client;

        // 初始化配置信息
        $api = 'http://openapi.tuling123.com/openapi/api/v2';
        $apiKey = config('services.turing_robot.apikey');
        $userId = config('services.turing_robot.userid');

        // 构建请求参数
        $data['body'] = json_encode([
            "reqType" => 0,
            "perception" => [
                "inputText" => ["text" => $text],
            ],
            "userInfo" => [
                "apiKey" => $apiKey,
                "userId" => $userId,
            ],
        ], JSON_UNESCAPED_UNICODE);

        // 发送 HTTP Post 请求
        $response = $http->post($api, $data);
        $res = json_decode($response->getBody(), true);
        
        // 对图灵机器人返回的结果 判断 处理
        $turing['second'] = '';
        if (count($res['results']) == 1) {
            $turing['first'] = ($res['results'][0]['resultType'] == 'image') ? $res['results'][0]['values']['image'] : $res['results'][0]['values']['text'];
        } elseif (count($res['results']) >= 2) {
            if ($res['results'][0]['resultType'] == 'text' && $res['results'][1]['resultType'] == 'image') {
                $turing['first'] = $res['results'][0]['values']['text'];
                $turing['second'] = $res['results'][1]['values']['image'];
            }
        }

        return $turing;
    }
}
