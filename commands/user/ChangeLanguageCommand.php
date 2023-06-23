<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class ChangeLanguageCommand.
 */
class ChangeLanguageCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "change_language";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Change the language";
	
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
		
		$languages = get_active_languages($telegram_id);
		
		$languages['keyboards'][] = [
			Keyboard::inlineButton([
				'text' => __('Suggest a new language', $user['user']['language']),
				'callback_data' => 'suggest_new_language'
			])
		];

        $options['text'] = __('Select a communication language:', $user['user']['language']);
        $options['reply_markup'] = Keyboard::make([
            'inline_keyboard' => $languages['keyboards'],
            'resize_keyboard' => true,
        ]);
        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);
	}
}
