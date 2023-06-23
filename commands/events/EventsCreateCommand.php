<?php

namespace Telegram\Bot\Commands;

/**
 * Class EventsCreateCommand.
 */
class EventsCreateCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "events_create";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Add event url.";
	
	/**
	 * {@inheritdoc}
	 */
	public function handle()
	{
		$update = $this->getUpdate();
		
		$telegram_id = $update->getMessage()->chat->id;

        $result = user_is_verified($telegram_id);
		
		if(!$result['status']) {
			return false;
		}
		
		$options = [
			'chat_id' => $telegram_id,
		];
		
		$options['text'] = __('Enter Event Url', $result['user']['language']);
		$this->telegram->sendMessage($options);
		
		set_command_to_last_message($this->name, $telegram_id);
	}
}
