<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class TeacherUpdateCommand.
 */
class TeacherArchiveCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "teacher_delete";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Delete your current active teacher.";
	
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
		
		if(!empty($update->getMessage()->reply_markup->inline_keyboard[0][0]['callback_data'])) { // archive collab confirmed
			$res = $lcApi->makeRequest('archive-teacher', ['telegram_id' => $telegram_id]);
			
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
				$options['text'] = "Are you sure you want to delete the teacher @" . $res['teacher']['publicAlias'] . '?';
				$options['reply_markup'] = Keyboard::make([
					'inline_keyboard' =>  [
						[
							Keyboard::inlineButton([
								'text' => 'Yes',
								'callback_data' => 'teacher_delete'
							])
						]
					],
					'resize_keyboard' => true,
				]);
			}
		}
		
	
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($this->name, $telegram_id);
	}
}
