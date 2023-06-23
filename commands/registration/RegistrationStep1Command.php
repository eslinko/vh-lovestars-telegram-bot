<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class RegistrationStep1Command extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "registration_step_1";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "First step of registration";
	
	/**
	 * @inheritdoc
	 */
	public function handle()
	{
		$update = $this->getUpdate();

		$telegram_id = $update->getMessage()->chat->id;

		$options = [
			'chat_id' => $telegram_id,
		];
		
		$options['text'] = 'Enter your E-mail.';
		$this->telegram->sendMessage($options);
	}
}