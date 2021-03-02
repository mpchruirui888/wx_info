<?php

namespace Tdong;

use Tdong\Helper\WxCommon;

require_once('./Helper/WxCommon.php');

class WxMiniProgram
{
    /** @var 小程序APPID */
    protected $WX_APP_ID = '';
    /** @var 小程序秘钥 */
    protected $WX_SECRET = '';

    /**
     * 小程序获取openid
     * @param string $code
     * @return mixed
     */
    public function getOpenid(string $code)
    {
        $appid = $this->WX_APP_ID; // 小程序APPID
        $secret = $this->WX_SECRET; // 小程序secret
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $secret . '&js_code=' . $code . '&grant_type=authorization_code';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return json_decode($res, true);
    }

    /**
     * 获取登录的用户信息
     * @param $data //获取到的用户的openid和access_token
     * @return mixed
     */
    public function getUserInfo($data)
    {
        //根据openid和access_token查询用户信息
        $access_token = $data['access_token'];
        $openid = $data['open_id'];
        $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
        $common = new WxCommon();
        $result = $common->curlIntDispose($get_user_info_url);
        // 解析json
        $user_obj = json_decode($result, true);
        return $user_obj;
    }
}