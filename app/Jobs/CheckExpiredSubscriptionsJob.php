<?php

namespace App\Jobs;

use App\Models\CustomerSubscriptionModel;
use App\Models\RouterCredential;
use App\MyHelper\RouterosAPI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batchSize = 100; // Number of users to process per batch

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Starting check for expired subscriptions.');

        // Create an instance of the RouterosAPI class
        $api = new RouterosAPI();

        // fetch router login credentials
        $routerCredential = RouterCredential::first();
        // Connect to the RouterOS
        if ($api->connect($routerCredential['ip_address'], $routerCredential['login'], $routerCredential['password'])) {
            $page = 1;

            do {
                $expiredSubscriptions = CustomerSubscriptionModel::where('end_date', '<', now())
                    ->where('status', 'active')
                    ->paginate($this->batchSize, ['*'], 'page', $page);

                foreach ($expiredSubscriptions as $subscription) {
                    Log::info("Processing subscription for PPPoE login: {$subscription->pppoe_login}");

                    // Find the existing secret
                    $existingSecret = $api->comm("/ppp/secret/print", [
                        ".proplist" => ".id",
                        "?name" => $subscription->pppoe_login,
                    ]);

                    if (!empty($existingSecret)) {
                        // Disable the secret
                        $api->comm("/ppp/secret/set", [
                            ".id" => $existingSecret[0]['.id'],
                            "disabled" => "yes",
                        ]);

                        // Kick the active session
                        $activeSession = $api->comm("/ppp/active/print", [
                            ".proplist" => ".id",
                            "?name" => $subscription->pppoe_login,
                        ]);

                        if (!empty($activeSession)) {
                            $api->comm("/ppp/active/remove", [
                                ".id" => $activeSession[0]['.id'],
                            ]);
                        }

                        $subscription->status = 'inactive';
                        $subscription->save();
                    }
                }

                $page++;
            } while ($expiredSubscriptions->isNotEmpty());

            $api->disconnect();
        } else {
            Log::error('Failed to connect to the router for subscription check.');
        }

        Log::info('Expired subscriptions checked and clients disconnected.');
    }
}
