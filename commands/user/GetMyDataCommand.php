<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class GetMyDataCommand.
 */
class GetMyDataCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "my_data";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "My data";
	
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
		
		$options['text'] = __("My public alias:", $user['user']['language']) . " " . $user['user']['publicAlias'] . "\n";
		$options['text'] .= __("My e-mail:", $user['user']['language']) . " " . $user['user']['email'];
		$options['reply_markup'] = Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Update Email', $user['user']['language']),
						'callback_data' => 'update_my_email'
					])
				],
				[
					Keyboard::inlineButton([
						'text' => __('Update Public Alias', $user['user']['language']),
						'callback_data' => 'update_my_public_alias'
					])
				],
				[
					Keyboard::inlineButton([
						'text' => __('Update Password', $user['user']['language']),
						'callback_data' => 'update_my_password'
					])
				],
                [
                    Keyboard::inlineButton([
                        'text' => __('Change the language', $user['user']['language']),
                        'callback_data' => 'change_language'
                    ])
                ]
			],
			'resize_keyboard' => true,
		]);
		
		$this->telegram->sendMessage($options);
	}
}
