<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class ClearAllInterestsQuestionCommand.
 */
class ClearAllInterestsQuestionCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "clear_all_interests_question";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Clear all interests question";
	
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
		
		$options['text'] = __("Are you sure you want to clear all your interests? This action cannot be undone.", $user['user']['language']);
		$options['reply_markup'] = Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('Yes, clear all', $user['user']['language']),
						'callback_data' => 'clear_all_interests'
					])
				],
				[
					Keyboard::inlineButton([
						'text' => __('No, cancel', $user['user']['language']),
						'callback_data' => 'my_interests_and_values'
					])
				]
			],
			'resize_keyboard' => true,
		]);
		
		$this->telegram->sendMessage($options);
	}
}
