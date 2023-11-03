<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
        DB::listen(function (QueryExecuted$event){
            $sql=$event->sql;
            $bindings=$event->bindings;
            $time=$event->time;
            $bindings=array_map(function ($binding){
                if(is_string($binding)){
                    return"'$binding'";
                }
                return $binding;
            },$bindings);
            str_replace('?','%s',$sql);
            $sql=sprintf($sql,...$bindings);
            Log::info('sql log',['sql'=>$sql,'time'=>$time]);
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
