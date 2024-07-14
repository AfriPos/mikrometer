<?php

namespace App\Jobs;

use App\Models\IPAddressesModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessIPAddresses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $network;
    protected $poolId;

    public function __construct($network, $poolId)
    {
        $this->network = $network;
        $this->poolId = $poolId;
    }

    public function handle()
    {
        // Generate and insert IP addresses for the given network and poolId
        $this->generateAndInsertIPAddresses($this->network, $this->poolId);
    }

    private function generateAndInsertIPAddresses($network, $poolId)
    {
        list($subnet, $mask) = explode('/', $network);
        $start = ip2long($subnet) + 1; // Exclude the first IP
        $end = $start + pow(2, (32 - $mask)) - 3; // Exclude the last IP

        $batchSize = 1000; // Process 1000 IPs at a time
        $data = [];

        for ($ip = $start; $ip <= $end; $ip += $batchSize) {
            $batchEnd = min($ip + $batchSize - 1, $end);

            for ($i = $ip; $i <= $batchEnd; $i++) {
                $data[] = [
                    'ip_address' => long2ip($i),
                    'pool_id' => $poolId
                ];
            }

            // Insert the batch into the database
            IPAddressesModel::insert($data);

            // Reset data array for next batch
            $data = [];
        }
    }
}
