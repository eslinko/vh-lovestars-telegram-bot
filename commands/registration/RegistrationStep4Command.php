<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class RegistrationStep4Command extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "registration_step_4";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Fourth step of registration";
	
	/**
	 * @inheritdoc
	 */
	public function handle()
	{
		$update = $this->getUpdate();

		$telegram_id = $update->getMessage()->chat->id;
		
		$lcApi = new \LCAPPAPI();
		$result = $lcApi->makeRequest('send-verification-email', ['telegram_id' => $telegram_id]);

		$options = [
			'chat_id' => $telegram_id,
		];
		
		$options['text'] = __('We have emailed you a code. Enter it here', $result['user']['language']);
		$this->telegram->sendMessage($options);
	}
}