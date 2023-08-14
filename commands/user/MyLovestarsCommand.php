<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class MyConnectionsCommand.
 */
class MyLovestarsCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "my_lovestars";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "My Lovestars";
	
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
        //show commands
        if(empty($user['user']['currentLovestarsCounter']))
            $options['text'] = sprintf(__("You have %s Lovestars",$user['user']['language']), 0);
        else
            $options['text'] = sprintf(__("You have %s Lovestars",$user['user']['language']), $user['user']['currentLovestarsCounter']);

        $this->telegram->sendMessage($options);

	}
}
