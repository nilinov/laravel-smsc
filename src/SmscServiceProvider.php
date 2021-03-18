<?php

namespace Papalapa\Laravel\Smsc;

use Illuminate\Support\ServiceProvider;
use Papalapa\Laravel\Smsc\Contracts\SenderContract;
use Papalapa\Laravel\Smsc\Services\CodeCreator;
use Papalapa\Laravel\Smsc\Services\CodeValidator;
use Papalapa\Laravel\Smsc\Services\GatewaySender;
use Papalapa\Laravel\Smsc\Services\LogSender;
use Papalapa\Laravel\Smsc\Services\TokenGenerator;

final class SmscServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishable();
        $this->mergeConfigFrom(__DIR__ . '/../config/smsc.php', 'smsc');

        $this->app->when(CodeCreator::class)
            ->needs('$size')->give(config('smsc.code_size'));

        $this->app->when(CodeValidator::class)
            ->needs('$lifetime')->give(config('smsc.code_lifetime'));

        $this->app->when(TokenGenerator::class)
            ->needs('$lifetime')->give(config('smsc.token_lifetime'));

        $this->app->when(GatewaySender::class)
            ->needs('$connection')->give(config('smsc.queue_connection'));
    }

    protected function registerPublishable(): void
    {
        $this->publishes([
            __DIR__ . '/../config/smsc.php' => config_path('smsc.php'),
        ], 'config');

        if (!class_exists('CreateSmsCodesTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/stub_create_sms_codes_table.php'
                => database_path('migrations/' . date('Y_m_d_His') . '_create_sms_codes_table.php'),
            ], 'migrations');
        }
    }

    public function register(): void
    {
        $sender = config('smsc.fake_send') ? LogSender::class : GatewaySender::class;
        $this->app->bind(SenderContract::class, $sender);
    }
}
