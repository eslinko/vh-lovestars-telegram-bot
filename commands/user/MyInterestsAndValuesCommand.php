<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class MyInterestsAndValuesCommand.
 */
class MyInterestsAndValuesCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "my_interests_and_values";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "My interests and values";
	
	/**
	 * {@inheritdoc}
	 */
	public function handle()
	{
		$update = $this->getUpdate();
		
		$telegram_id = $update->getMessage()->chat->id;
		
		$user = user_is_verified($telegram_id);
		
		if(!$user['status']) {
			return false;
		}
		
		$options = [
			'chat_id' => $telegram_id,
		];
		
		$options['text'] = __("What are your interests, hobbies and values that you want to share with other people? You can use any language. You can just list topics or even write an essay about who you really are. We will use this information in order to bring you closer to people sharing those values and interests. So, the more specific you are, the more support we can provide", $user['user']['language']);
		
		$this->telegram->sendMessage($options);
		set_command_to_last_message($this->name, $telegram_id);
	}
}
