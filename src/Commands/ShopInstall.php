<?php

namespace Wsmallnews\Shop\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;

class ShopInstall extends Command
{
    public $signature = 'sn-shop:install';

    public $description = 'Install the sn-shop';

    public function handle(): int
    {
        $this->components->info('Installing Sn-shop...');

        $this->components->info('Publishing configuration...');

        if (! $this->configExists('sn-shop')) {
            $this->publishConfiguration();
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->components->info('Overwriting configuration file...');
                $this->publishConfiguration(forcePublish: true);
            } else {
                $this->components->info('Existing configuration was not overwritten');
            }
        }

        if (confirm('Publish `Spatie\LaravelSettings` package`s database migrations?')) {
            $this->call('vendor:publish', [
                '--provider' => 'Spatie\LaravelSettings\LaravelSettingsServiceProvider',
                '--tag' => 'migrations',
            ]);
        }

        if (confirm('Run database migrations?')) {
            $this->call('migrate');
        }

        DB::transaction(function () {

            // @sn todo 如何检测并创建一个管理员
            // if (class_exists(Staff::class) && ! Staff::whereAdmin(true)->exists()) {
            //     $this->components->info('First create a lunar admin user');
            //     $this->call('lunar:create-admin');
            // }

            // 下面是需要初始化的数据
        });

        $this->components->info('Publishing Filament assets');
        $this->call('filament:assets');

        $this->components->info('Sn-shop is now installed 🚀');

        if (confirm('Would you like to show some love by giving us a star on GitHub?')) {
            match (PHP_OS_FAMILY) {
                'Darwin' => exec('open https://github.com/Wsmallnews/shop'),
                'Linux' => exec('xdg-open https://github.com/Wsmallnews/shop'),
                'Windows' => exec('start https://github.com/Wsmallnews/shop'),
            };

            $this->components->info('Thank you!');
        }

        $this->comment('All done');

        return self::SUCCESS;
    }

    /**
     * 检查如果给定文件或目录是否存在
     */
    private function configExists(string $fileName): bool
    {
        if (! File::isDirectory(config_path($fileName))) {
            return false;
        }

        return ! empty(File::allFiles(config_path($fileName)));
    }

    /**
     * Publishes configuration for the Service Provider.
     */
    private function publishConfiguration(bool $forcePublish = false): void
    {
        $params = [
            '--tag' => 'sn-shop',
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
