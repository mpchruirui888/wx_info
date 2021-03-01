<?php

namespace Tdong;

class  WxMobile
{
    /** @var string 公众号APPID */
    protected $WX_MOBILE_APP_ID = '';

    /** @var string 公众号秘钥 */
    protected $WX_MOBILE_SECRET = '';


    /**
     * Wx constructor.
     * @param $appid
     * @param $secret
     */
    public function __construct($appid = '', $secret = '')
    {
        if (!isset($appid) || !isset($secret)) return false;
        $this->WX_MOBILE_APP_ID = $appid;
        $this->WX_MOBILE_SECRET = $secret;
    }

    /**
     * 获取微信公众号code以便后续获取openid和access
     */
    public function getWxMobileCode()
    {
        $appid = $this->WX_MOBILE_APP_ID;
        // 跳转的链接地址
        $redirect_uri = 'https://www.baidu.com';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $redirect_uri . '&response_type=code&scope=snsapi_base&state=cytx#wechat_redirect';
        $result = $this->curlIntDispose($url);
        return $result;
    }

    /**
     * 公众号获取openid和access_token
     * @param $code
     * @return mixed
     */
    public function wxMobileGetOpenid($code)
    {
        $appid = $this->WX_MOBILE_APP_ID;
        $secret = $this->WX_MOBILE_SECRET;
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
        $result = $this->curlIntDispose($get_token_url);
        $result_array = json_decode($result, true);
        return $result_array;
    }

    /**
     * 远程调用的封装处理
     * @param $url
     * @return bool|string
     */
    public function curlIntDispose($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}