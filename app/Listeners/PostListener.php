<?php

namespace App\Listeners;

use App\Events\PostEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class PostListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostEvent  $event
     * @return void
     */
    public function handle(PostEvent $event)
    {
	    //获取事件中保存的信息
	    $user = $event->getUser();
	    $ip = $event->getIp();
	    $timestamp = $event->getTimestamp();
		$msg = json_encode(['ip' => $ip, 'time' => $timestamp, 'user' => $user]);
	    Log::info($msg);
    }
}
