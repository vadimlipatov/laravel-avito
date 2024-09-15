<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Sms\ArraySender;
use App\Services\Sms\SmsRu;
use App\Services\Sms\SmsSender;

class SmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmsSender::class, function ($app) {
            $config = $app->make('config')->get('sms');

            switch ($config['driver']) {
                case 'sms.ru':
                    $params = $config['drivers']['sms.ru'];
                    if (!empty($params['url'])) {
                        return new SmsRu($params['api_id'], $params['url']);
                    }
                    return new SmsRu($config['api_id']);

                case 'array':
                    return new ArraySender();

                default:
                    throw new \InvalidArgumentException('Undefined sms driver ' . $config['driver']);
            }
        });
    }
}
