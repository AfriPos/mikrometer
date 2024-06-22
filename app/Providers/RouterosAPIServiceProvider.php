<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\MyHelper\RouterosAPI;
use App\Models\RouterCredential;
use App\Services\RouterosService;

class RouterosAPIServiceProvider extends ServiceProvider
{

    public $API = [], $routeros_data = [], $connection;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RouterosAPI::class, function ($app) {
            return new RouterosAPI();
        });
    }


    public function check_routeros_connection($data)
    {
        $routeros_db = RouterCredential::where('ip_address', $data['ip_address'])->get();
        if (count($routeros_db) > 0) :
            $API = new RouterosAPI;
            $connection = $API->connect($routeros_db[0]['ip_address'], $routeros_db[0]['login'], $routeros_db[0]['password']);

            if (!$connection)
                return false;

            $this->API = $API;
            $this->connection = $connection;
            $this->routeros_data = [
                'identity' => $this->API->comm('/system/identity/print')[0]['name'],
                'ip_address' => $routeros_db[0]['ip_address'],
                'login' => $routeros_db[0]['login'],
                'connect' => $connection
            ];
            return true;
        endif;
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
