<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WelcomeMessage extends Command
{
	/**
	 * 命令名称，在控制台执行命令时用到
	 *
	 * @var string
	 */
	protected $signature = 'welcome:message';

	/**
	 * 命令描述
	 *
	 * @var string
	 */
	protected $description = 'print welcome message';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 命令具体执行逻辑放在这里
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->info('欢迎访问 Laravel 学院!');
	}
}