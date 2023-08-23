<?php

namespace Telegram\Bot\Commands;

/**
 * Class SuggestNewLanguageCommand.
 */
class SuggestNewLanguageCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "suggest_new_language";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Suggest a new language";
	
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
		
		//$options['text'] = __('Enter the language in which you..', $user['user']['language']);
		//$this->telegram->sendMessage($options);
        \TGKeyboard::hideKeyboard($telegram_id, $this->telegram, __('Enter the language in which you..', $user['user']['language']));
        set_command_to_last_message($this->name, $telegram_id);
	}
}
