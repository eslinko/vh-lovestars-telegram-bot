<?php

namespace Telegram\Bot\Commands;

/**
 * Class TeacherSetActiveCommand.
 */
class TeacherSetActiveCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "set_active_teacher";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Sets Teacher to Work.";
	
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
		
		$options['text'] = 'Enter publicAlias of Teacher';
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($this->name, $telegram_id);
	}
}
