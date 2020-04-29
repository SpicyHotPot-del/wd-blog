<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * @var User 用户模型
	 */
	protected $user;

	/**
	 * @var Agent Agent对象
	 */
	protected $agent;

	/**
	 * @var string IP地址
	 */
	protected $ip;

	/**
	 * @var int 登录时间戳
	 */
	protected $timestamp;

	protected $agents;

	/**
	 * PostEvent constructor.
	 * @param $user
	 * @param $ip
	 * @param $timestamp
	 */
    public function __construct($user, $ip, $timestamp, $agents='')
    {
	    $this->user = $user;
	    $this->ip = $ip;
	    $this->timestamp = $timestamp;
	    $this->agents = $agents;
    }

	public function getUser()
	{
		return $this->user;
	}

	public function getAgent()
	{
		return $this->agent;
	}

	public function getIp()
	{
		return $this->ip;
	}

	public function getTimestamp()
	{
		return $this->timestamp;
	}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }


}
