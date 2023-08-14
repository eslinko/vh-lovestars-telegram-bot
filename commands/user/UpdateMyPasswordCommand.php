<?php

namespace Telegram\Bot\Commands;

/**
 * Class UpdateMyPasswordCommand.
 */
class UpdateMyPasswordCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "update_my_password";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Update my password.";
	
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
		
		/*$options = [
			'chat_id' => $telegram_id,
		];
		
		$options['text'] = __('Enter your new password.', $user['user']['language']);
		$this->telegram->sendMessage($options);*/


        \TGKeyboard::hideKeyboard($telegram_id, $this->telegram, __('Enter your new password.', $user['user']['language']));
        set_command_to_last_message($this->name, $telegram_id);
    }
}
