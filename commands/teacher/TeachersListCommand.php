<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class TeachersListCommand.
 */
class TeachersListCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "list_my_teachers";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Return a list of all user’s teachers";
	
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
		
		$lcApi = new \LCAPPAPI();
		$return_data = $lcApi->makeRequest('get-teachers', ['telegram_id' => $telegram_id]);
		$teachers = json_decode($return_data['teachers'], true);
		
		$options = [
			'chat_id' => $telegram_id,
		];
		
		if(empty($teachers)) {
			$options['text'] = 'You don\'t have any teachers created yet.';
			$options['reply_markup'] = Keyboard::make([
				'inline_keyboard' =>  [
					[
						Keyboard::inlineButton([
							'text' => 'Create your first teacher',
							'callback_data' => 'teacher_create'
						])
					]
				],
				'resize_keyboard' => true,
			]);
		} else {
			$text = '';
//			$options['text'] = "Your teachers below (you can change them by clicking on the one you want):";
//			$inline_keyboard = [];
			foreach ($teachers as $teacher) {
				$text .= "Public alias: @{$teacher['publicAlias']} \n";
				$text .= "Title: {$teacher['title']} \n";
				$desc = trim(strip_tags($teacher['description']));
				$text .= "Description: {$desc}\n";
				$text .= "Hashtags: {$teacher['hashtags']} \n\n";
//				$inline_keyboard[] = [
//					Keyboard::inlineButton([
//						'text' => $teacher['title'],
//						'callback_data' => 'teacher_update|' . $teacher['id']
//					])
//				];
			}
			
			$options['text'] = $text;
			
//			$options['reply_markup'] = Keyboard::make([
//				'inline_keyboard' =>  $inline_keyboard,
//				'resize_keyboard' => true,
//			]);
		}
		
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($this->name, $telegram_id);
	}
}
