<?php

namespace Telegram\Bot\Commands;

/**
 * Class UpdateMyEmailConfirmCodeCommand.
 */
class UpdateMyEmailConfirmCodeCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "update_my_email_confirm_code";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Update my email confirmation code.";
	
	/**
	 * {@inheritdoc}
	 */
	public function handle()
	{
		$update = $this->getUpdate();
		
		$telegram_id = $update->getMessage()->chat->id;
		
		$user = user_is_verified($telegram_id);
		
		if(!$user['status']) {
			return false;
		}
		
		$options = [
			'chat_id' => $telegram_id,
		];
		
		$options['text'] = __('Enter the code you received in your new email to confirm the email change', $user['user']['language']);
		$this->telegram->sendMessage($options);
		set_command_to_last_message($this->name, $telegram_id);
	}
}
