<?php

namespace Wsmallnews\Shop\Pages\Settings;

use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Wsmallnews\Shop\Settings\WechatPay as WechatPaySetting;

class WechatPay extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = WechatPaySetting::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\TextInput::make('mch_id')->label('商户号')
                    ->placeholder('请输入商户号')->required(),
                Components\TextInput::make('mch_secret_key')->label('商户秘钥')
                    ->placeholder('请输入商户秘钥')->required(),

                Components\FileUpload::make('mch_secret_cert')->label('商户私钥证书')
                    ->disk('local')
                    ->directory('certs/wechat')
                    ->acceptedFileTypes(['pem', 'crt'])
                    ->placeholder('请输入商户私钥证书')
                    ->uploadingMessage('证书上传中...')
                    ->required(),
                Components\TextInput::make('mch_public_cert_path')->label('商户公钥证书')
                    ->disk('local')
                    ->directory('certs/wechat')
                    ->acceptedFileTypes(['pem', 'crt'])
                    ->placeholder('请输入商户公钥证书')
                    ->uploadingMessage('证书上传中...')
                    ->required(),

                Components\TextInput::make('sub_mch_id')->label('子商户id')
                    ->placeholder('请输入子商户id')->required(),

                Components\TextInput::make('wechat_public_cert_id')->label('微信支付公钥ID')
                    ->disk('local')
                    ->directory('certs/wechat')
                    ->acceptedFileTypes(['pem', 'crt'])
                    ->placeholder('请输入微信支付公钥ID')
                    ->uploadingMessage('证书上传中...'),
                Components\TextInput::make('wechat_public_cert')->label('微信支付公钥证书')
                    ->disk('local')
                    ->directory('certs/wechat')
                    ->acceptedFileTypes(['pem', 'crt'])
                    ->placeholder('请输入微信支付公钥证书')
                    ->uploadingMessage('证书上传中...'),
            ]);
    }
}
