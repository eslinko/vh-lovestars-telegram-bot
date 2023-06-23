<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class SuccesfullyRegistratedCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "succesfully_registrated";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Message about successful registration";
	
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
		
		$options['text'] = __('Congratulations, you have successfully registered!', $result['user']['language']);
		$options['reply_markup'] = Keyboard::make([
			'inline_keyboard' =>  [
				[
					Keyboard::inlineButton([
						'text' => __('View a list of commands.', $result['user']['language']),
						'callback_data' => 'help'
					])
				]
			],
			'resize_keyboard' => true,
		]);
		$this->telegram->sendMessage($options);
	}
}