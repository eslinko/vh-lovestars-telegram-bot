<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class TeacherUpdateTitleCommand.
 */
class TeacherUpdateTitleCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "teacher_update_title";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Update title of active teacher.";
	
	/**
	 * {@inheritdoc}
	 */
	public function handle()
	{
		$update = $this->getUpdate();
		
		$telegram_id = $update->getMessage()->chat->id;
		
		if(!user_is_verified($telegram_id)['status']) {
			return false;
		}
		
		$options = [
			'chat_id' => $telegram_id,
		];
		
		$lcApi = new \LCAPPAPI();
		$res = $lcApi->makeRequest('get-active-teacher', ['telegram_id' => $telegram_id]);
		
		if($res['status'] === 'error') {
			$options['text'] = $res['text'];
			$options['reply_markup'] = Keyboard::make([
				'inline_keyboard' =>  [
					[
						Keyboard::inlineButton([
							'text' => 'Check Your Teacher.',
							'callback_data' => 'list_my_teachers'
						])
					]
				],
				'resize_keyboard' => true,
			]);
		} else {
			$options['text'] = 'Enter Teacher Name';
		}
		
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($this->name, $telegram_id);
	}
}
