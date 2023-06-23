<?php

namespace Telegram\Bot\Commands;

/**
 * Class TeacherCreateStep3Command.
 */
class TeacherCreateStep3Command extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "teacher_create_step_3";
	
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
		
		$options['text'] = 'Enter Teacher Description';
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($this->name, $telegram_id);
	}
}
