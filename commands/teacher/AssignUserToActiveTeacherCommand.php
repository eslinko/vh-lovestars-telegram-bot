<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class AssignUserToActiveTeacherCommand.
 */
class AssignUserToActiveTeacherCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "assign_user_to_active_teacher";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Assign the user to an active teacher.";
	
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
			$options['text'] = "You’re going to add access for another user to the teacher " . $res['teacher']['title'] . "(@" . $res['teacher']['publicAlias'] . "). \n Please specify the user alias.";
			$command_name = $this->name;
		}
		
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($command_name, $telegram_id);
	}
}
