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

		$result = user_is_verified($telegram_id);

		$options = [
			'chat_id' => $telegram_id,
		];

		if(!empty($result['user']['email'])) {
			$options['text'] = __('You are already registered', $result['user']['language']);
			$this->telegram->sendMessage($options);
			return false;
		}
		
		$options['text'] = 'Enter your E-mail.';
		$this->telegram->sendMessage($options);
	}
}