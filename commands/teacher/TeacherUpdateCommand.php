<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class TeacherUpdateCommand.
 */
class TeacherUpdateCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "teacher_update";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Update your current active teacher.";
	
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
			$command_name = 'help';
		} else {
//			$parts = explode('teacher_update_', $update->callbackQuery->data);
//			if(!empty($parts[1])) {
//				$command_name = 'teacher_do_update_' . $parts[1];
//			} else {
				$options['text'] = 'What do you want to change at the teacher @'.$res['teacher']['publicAlias'].'?';
				$options['reply_markup'] = Keyboard::make([
					'inline_keyboard' =>  [
						[
							Keyboard::inlineButton([
								'text' => 'Public Alias',
								'callback_data' => 'teacher_update_public_alias'
							]),
							Keyboard::inlineButton([
								'text' => 'Title',
								'callback_data' => 'teacher_update_title'
							])
						],
						[
							Keyboard::inlineButton([
								'text' => 'Description',
								'callback_data' => 'teacher_update_description'
							]),
							Keyboard::inlineButton([
								'text' => 'Hashtags',
								'callback_data' => 'teacher_update_hashtags'
							])
						]
					],
					'resize_keyboard' => true,
				]);
				$command_name = $this->name;
//			}
		}
		
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($command_name, $telegram_id);
	}
}
