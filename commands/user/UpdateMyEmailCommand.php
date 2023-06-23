<?php

namespace Telegram\Bot\Commands;

/**
 * Class UpdateMyEmailCommand.
 */
class UpdateMyEmailCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "update_my_email";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Update my email.";
	
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
		
		$options['text'] = __('Enter your new e-mail.', $user['user']['language']);
		$this->telegram->sendMessage($options);
		set_command_to_last_message($this->name, $telegram_id);
	}
}
