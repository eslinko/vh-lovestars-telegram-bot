<?php

namespace Telegram\Bot\Commands;

/**
 * Class UpdateMyPublicAliasCommand.
 */
class UpdateMyPublicAliasCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "update_my_public_alias";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Update my public alias.";
	
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
		
		$options['text'] = __('Enter your new public alias.', $user['user']['language']);
		$this->telegram->sendMessage($options);*/
        \TGKeyboard::hideKeyboard($telegram_id, $this->telegram, __('Enter your new public alias.', $user['user']['language']));
		set_command_to_last_message($this->name, $telegram_id);

	}
}
