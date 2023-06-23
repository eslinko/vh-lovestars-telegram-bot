<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class RegistrationStep3Command extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "registration_step_3";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Third step of registration";
	
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
		
		$options['text'] = __('Enter your password.', $result['user']['language']);
		$this->telegram->sendMessage($options);
	}
}