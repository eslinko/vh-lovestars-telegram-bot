<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class TeacherCheckActiveCommand.
 */
class TeacherCheckActiveCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "check_active_teacher";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Check you current active Teacher.";
	
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
			$options['text'] = 'Teacher '.$res['teacher']['title'].'(@' . $res['teacher']['publicAlias'] . ') set as an active.';
		}
		
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($this->name, $telegram_id);
	}
}
