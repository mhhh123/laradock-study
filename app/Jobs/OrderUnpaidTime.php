<?php

namespace App\Jobs;

use App\Services\Order\OrderServices;
use App\Services\SystemServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class  OrderUnpaidTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;
    private $orderId;
    /**
     * Create a new job instance.
     *
     *
     */
    public function __construct($userId,$orderId)
    {
        $this->userId=$userId;
        $this->orderId=$orderId;
        $delayTime=SystemServices::getInstance()->getOrderUnpaidDelayMinutes();
        $this->delay(now()->addMinutes($delayTime));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        OrderServices::getInstance()->cancel($this->userId,$this->orderId);
    }
}
