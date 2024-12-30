<?php

namespace Wsmallnews\Shop\Resources\Pages\Settings;

use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Wsmallnews\Shop\Settings\WechatPay as WechatPaySetting;
use Yansongda\Pay\Pay;

class WechatPay extends SettingsPage
{
    protected static ?string $navigationGroup = '设置';

    protected static ?string $navigationLabel = '微信支付配置';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $slug = '/shop/wechat-pay';

    protected static ?int $navigationSort = 2;

    protected static string $settings = WechatPaySetting::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Components\Section::make()
                ->schema([
                    Components\Radio::make('mode')->label('模式')
                        ->options([
                            Pay::MODE_NORMAL => '普通',
                            Pay::MODE_SERVICE => '服务商',
                            Pay::MODE_SANDBOX => '沙箱',
                        ])
                        ->required()
                        ->columnSpan(2),

                    Components\TextInput::make('mch_id')->label('商户号')
                        ->placeholder('请输入商户号')
                        ->required()
                        ->columnSpan(2),
                    Components\TextInput::make('mch_secret_key')->label('商户秘钥')
                        ->placeholder('请输入商户秘钥')
                        ->required()
                        ->columnSpan(2),

                    Components\FileUpload::make('mch_secret_cert')->label('商户私钥证书')
                        ->disk('local')
                        ->directory('certs/wechat')
                        ->acceptedFileTypes(['pem', 'crt'])
                        ->placeholder('请输入商户私钥证书')
                        ->uploadingMessage('证书上传中...')
                        ->required()
                        ->columnSpan(2),
                    Components\FileUpload::make('mch_public_cert_path')->label('商户公钥证书')
                        ->disk('local')
                        ->directory('certs/wechat')
                        ->acceptedFileTypes(['pem', 'crt'])
                        ->placeholder('请输入商户公钥证书')
                        ->uploadingMessage('证书上传中...')
                        ->required()
                        ->columnSpan(2),

                    Components\TextInput::make('sub_mch_id')->label('子商户号')
                        ->placeholder('请输入子商户号')
                        ->required()
                        ->columnSpan(2),

                    Components\FileUpload::make('wechat_public_cert_id')->label('微信支付公钥ID')
                        ->disk('local')
                        ->directory('certs/wechat')
                        ->acceptedFileTypes(['pem', 'crt'])
                        ->placeholder('请输入微信支付公钥ID')
                        ->uploadingMessage('证书上传中...')
                        ->columnSpan(2),
                    Components\FileUpload::make('wechat_public_cert')->label('微信支付公钥证书')
                        ->disk('local')
                        ->directory('certs/wechat')
                        ->acceptedFileTypes(['pem', 'crt'])
                        ->placeholder('请输入微信支付公钥证书')
                        ->uploadingMessage('证书上传中...')
                        ->columnSpan(2),
                ])->columns(3),
        ]);
    }
}
