<?php

namespace Telegram\Bot\Commands;

/**
 * Class TeacherCreateCommand.
 */
class TeacherCreateCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "teacher_create";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Create your teacher.";
	
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
		
		$options['text'] = 'Enter Teacher Name';
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($this->name, $telegram_id);
	}
}
