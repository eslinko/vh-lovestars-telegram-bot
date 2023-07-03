<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class RegistrationStepInvitationCodeCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "registration_step_invitation_code";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Step of registration with invitation code";
	
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
		
		$options['text'] = __('Please provide your invitation code', $result['user']['language']);
		$this->telegram->sendMessage($options);
	}
}