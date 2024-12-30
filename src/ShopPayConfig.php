<?php

namespace Wsmallnews\Shop;

use Wsmallnews\Pay\Contracts\PayConfigInterface;
use Wsmallnews\Pay\Exceptions\PayException;

class ShopPayConfig implements PayConfigInterface
{
    // protected string $pay_method;
    protected string $platform;

    protected array $payConfig;

    public function __construct($platform, $pay_method)
    {
        // $this->pay_method = $pay_method;

        $this->platform = $platform ?: request()->header('platform', null);

        if (!$this->platform) {
            throw new PayException('缺少用户端平台参数');
        }

        $this->payConfig = $this->initPayConfig();
    }



    public function getPayConfig($pay_method): array
    {
        $config = $this->payConfig[$pay_method];


        return $config;
    }


    public function getFinalConfig($pay_method): array
    {
        $config = $this->getPayConfig($pay_method);

        if (in_array($pay_method, ['wechat', 'alipay', 'douyin'])) {
            $this->yansongdaBaseConfig();
        }

        return $config;
    }


    protected function initPayConfig(): array
    {
        $payConfig = [
            'money' => [
                ''
            ],
            'score' => [
                'real_rate' => function () {
                    return 0.1;
                }
            ],
            'wechat' => [

            ]
        ];

        return $payConfig;
    }



    


    // public function getNotifyUrl()
    // {
    //     return request()->domain() . '/estore/api.pay/notify/payment/' . $this->payment . '/platform/' . $this->platform;
    // }


    // public function getRefundNotifyUrl()
    // {
    //     return request()->domain() . '/estore/api.pay/refundNotify/payment/' . $this->payment . '/platform/' . $this->platform;
    // }








    /**
     * 获取对应的支付方法名
     *
     * @param strign $payment
     * @return string
     */
    // public function getPayMethod(): string
    // {
    //     $method = [
    //         'WechatOfficialAccount' => 'mp',        //公众号支付 Collection
    //         'WechatMiniProgram' => 'mini',       //小程序支付 Collection 
    //         'H5' => 'wap',                      //手机网站支付 Response
    //         'App' => 'app',                      // APP 支付 JsonResponse
    //         'TtMiniProgram' => 'mini',
    //     ];

    //     return $method[$this->platform];
    // }


    /**
     * 初始化支付配置参数
     */
    // protected function initPayConfig(): array
    // {
    //     $platformPayConfig = $this->getPlatformPayConfig();      // 格式化的 平台支付配置

    //     $payConfig = $this->getDbPayConfig($platformPayConfig);                       // 支付必要参数

    //     $payConfig = $this->{'format' . str::studly($this->payment) . 'Config'}($payConfig, $platformPayConfig);

    //     return $payConfig;
    // }



    /**
     * 获取平台配置参数
     *
     * @return array
     */
    // protected function getPlatformPayConfig()
    // {
    //     $platformSetting = [
    //         'pay_methods' => ['wechat', 'money'],
    //         'pay_config_id' => 2,
    //         'app_id' => '123112344'
    //     ];

    //     if (!in_array($this->payment, $paymentMethods)) {
    //         throw new PayException('当前平台不支持此支付渠道');
    //     }


    //     return $platformSetting;

    //     $platformSetting = estore_setting("estore_platform_" . Str::snake($this->platform));

    //     $paymentMethods = $platformSetting->payment_methods ?? [];

    //     if (!in_array($this->payment, $paymentMethods)) {
    //         throw new PayException('当前平台不支持此支付渠道');
    //     }

    //     $pay_config_id = $platformSetting->{'payment_' . $this->payment} ?? 0;
    //     $app_id = $platformSetting->app_id ?? '';

    //     return compact('pay_config_id', 'app_id');
    // }



    // protected function getDbPayConfig($platformPayConfig)
    // {
    //     $pay_config_id = $platformPayConfig['pay_config_id'] ?? 0;

    //     if ($pay_config_id) {
    //         $payConfig = PayConfig::normal()->find($pay_config_id);
    //     }

    //     if (!isset($payConfig) || !$payConfig
    //     ) {
    //         throw new PayException('支付配置未找到');
    //     }

    //     return $payConfig->params;
    // }



    // /**
    //  * 格式化微信官方渠道配置参数
    //  */
    // protected function formatWechatConfig($payConfig, $platformPayConfig = [], $type = 'normal')
    // {
    //     $payConfig['mode'] = (int)($payConfig['mode'] ?? 0);       // 格式化为 int
    //     if ($payConfig['mode'] == 2 && $type == 'sub_mch') {
    //         // 服务商模式，但需要子商户直连 ，重新定义 config(商家转账到零钱)
    //         $payConfig = [
    //             'mch_id' => $payConfig['sub_mch_id'],
    //             'mch_secret_key' => $payConfig['sub_mch_secret_key'],
    //             'mch_secret_cert' => $payConfig['sub_mch_secret_cert'],
    //             'mch_public_cert_path' => $payConfig['sub_mch_public_cert_path'],
    //         ];
    //         $payConfig['mode'] = 0;        // 临时改为普通商户
    //     }

    //     if ($payConfig['mode'] === 2
    //     ) {
    //         // 首先将平台配置的 app_id 初始化到配置中
    //         $payConfig['mp_app_id'] = $payConfig['app_id'];       // 服务商关联的公众号的 appid
    //         $payConfig['sub_app_id'] = $platformPayConfig['app_id'];        // 服务商特约子商户
    //     } else {
    //         $payConfig['app_id'] = $platformPayConfig['app_id'];
    //     }

    //     switch ($this->platform) {
    //         case 'WechatMiniProgram':
    //             $payConfig['_type'] = 'mini';          // 小程序提现，需要传 _type = mini 才能正确获取到 appid
    //             if ($payConfig['mode'] === 2) {
    //                 $payConfig['sub_mini_app_id'] = $payConfig['sub_app_id'];
    //                 unset($payConfig['sub_app_id']);
    //             } else {
    //                 $payConfig['mini_app_id'] = $payConfig['app_id'];
    //                 unset($payConfig['app_id']);
    //             }
    //             break;
    //         case 'WechatOfficialAccount':
    //             $payConfig['_type'] = 'mp';          // 小程序提现，需要传 _type = mp 才能正确获取到 appid
    //             if ($payConfig['mode'] === 2) {
    //                 $payConfig['sub_mp_app_id'] = $payConfig['sub_app_id'];
    //                 unset($payConfig['sub_app_id']);
    //             } else {
    //                 $payConfig['mp_app_id'] = $payConfig['app_id'];
    //                 unset($payConfig['app_id']);
    //             }
    //             break;
    //         case 'App':
    //         case 'H5':
    //         default:
    //             break;
    //     }

    //     // @sn 支付回调地址设置位置
    //     // $payConfig['notify_url'] = request()->domain() . '/addons/shopro/pay/notify/payment/wechat/platform/' . $this->platform;

    //     $payConfig['mch_secret_cert'] = ROOT_PATH . 'public' . $payConfig['mch_secret_cert'];
    //     $payConfig['mch_public_cert_path'] = ROOT_PATH . 'public' . $payConfig['mch_public_cert_path'];

    //     return $payConfig;
    // }




    // /**
    //  * 格式化支付宝官方渠道配置参数
    //  *
    //  * @param [type] $params
    //  * @return void
    //  */
    // protected function formatAlipayConfig($payConfig, $data = [])
    // {
    //     // @sn 支付回调地址设置位置
    //     // $payConfig['notify_url'] = request()->domain() . '/addons/shopro/pay/notify/payment/alipay/platform/' . $this->platform;

    //     if (in_array($this->platform, ['WechatOfficialAccount', 'WechatMiniProgram', 'H5'])) {
    //         // app 支付不能带着个参数
    //         $payConfig['return_url'] = str_replace('&amp;', '&', request()->param('return_url', ''));
    //     }

    //     $end = substr($payConfig['app_secret_cert'], -4);
    //     if ($end == '.crt') {
    //         $payConfig['app_secret_cert'] = ROOT_PATH . 'public' . $payConfig['app_secret_cert'];
    //     }
    //     $payConfig['alipay_public_cert_path'] = ROOT_PATH . 'public' . $payConfig['alipay_public_cert_path'];
    //     $payConfig['app_public_cert_path'] = ROOT_PATH . 'public' . $payConfig['app_public_cert_path'];
    //     $payConfig['alipay_root_cert_path'] = ROOT_PATH . 'public' . $payConfig['alipay_root_cert_path'];

    //     return $payConfig;
    // }



    // /**
    //  * 格式化微信官方渠道配置参数
    //  */
    // protected function formatDouyinConfig($payConfig, $platformPayConfig = [], $type = 'normal')
    // {
    //     $payConfig['mode'] = (int)($payConfig['mode'] ?? 0);       // 格式化为 int

    //     $payConfig['mini_app_id'] = $platformPayConfig['app_id'];

    //     switch ($this->platform) {
    //         case 'TtMiniProgram':
    //             break;
    //         default:
    //             break;
    //     }

    //     // $payConfig['notify_url'] = request()->domain() . '/addons/shopro/pay/notify/payment/douyin/platform/' . $this->platform;

    //     return $payConfig;
    // }



    /**
     * yansongda 基础配置
     *
     * @return array
     */
    protected function yansongdaBaseConfig(): array
    {
        $log_path = RUNTIME_PATH . 'log/pay/';
        if (!is_dir($log_path)) {
            @mkdir($log_path, 0755, true);
        }

        return [
            'logger' => [ // optional
                'enable' => true,
                'file' => $log_path . 'pay.log',
                'level' => config('app_debug') ? 'debug' : 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'daily', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
                // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
            ],
        ];
    }

}
