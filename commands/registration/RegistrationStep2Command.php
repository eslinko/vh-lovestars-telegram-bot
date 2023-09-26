<?php

namespace Telegram\Bot\Commands;

class RegistrationStep2Command extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "registration_step_2";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Second step of registration";
	
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

		if(!empty($result['user']['full_name']) && !empty($result['user']['publicAlias'])) {
			$options['text'] = __('You are already registered', $result['user']['language']);
			$this->telegram->sendMessage($options);
			return false;
		}
		
		$options['text'] = __('Enter your public alias.', $result['user']['language']);
		$this->telegram->sendMessage($options);
	}
}