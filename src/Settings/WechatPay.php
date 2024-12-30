<?php

namespace Wsmallnews\Shop\Settings;

use Spatie\LaravelSettings\Settings;

class WechatPay extends Settings
{
    // 必填-商户号
    public string $mch_id;

    // 必填-v3 商户秘钥
    public string $mch_secret_key;

    // 必填-商户私钥 字符串或路径
    public string $mch_secret_cert;

    // 必填-商户公钥证书路径
    public string $mch_public_cert_path;

    // 选填-服务商模式下，子商户id
    public string $sub_mch_id;

    // 选填-微信支付公钥 id
    public string $wechat_public_cert_id;

    // 选填-微信支付公钥证书
    public string $wechat_public_cert;

    // 选填-模式
    public int $mode;

    public static function group(): string
    {
        return 'shop_wechat_pay';
    }

    // {
    //     "app_id":"",
    //     "mode":"0",

    //     "mch_secret_cert":"\/uploads\/20240627\/fa01cd04ec0bc947dacac50e77aeb398.pem",
    //     "mch_public_cert_path":"\/uploads\/20240627\/4a0f376c47f145415e199ba9b5bab5c0.pem",
    //     "alipay_public_cert_path":"",
    //     "app_public_cert_path":"",
    //     "alipay_root_cert_path":"",
    //     "app_secret_cert":"",
    //     "service_provider_id":"",
    //     "mch_id":"1481069012",
    //     "mch_secret_key":"34010835FA24B45BAB96FEFB1A9599B4",
    //     "sub_mch_id":"",
    //     "sub_mch_secret_key":"",
    //     "sub_mch_public_cert_path":"",
    //     "sub_mch_secret_cert":""
    // }
}
