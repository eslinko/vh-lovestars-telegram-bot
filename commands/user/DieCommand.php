<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class DeleteConnectionsCommand.
 */
class DieCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "die";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Delete yourself";
	
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

        $lcApi = new \LCAPPAPI();
        $data = $lcApi->makeRequest('die', ['telegram_id' => $telegram_id]);

        $options = [
            'chat_id' => $telegram_id,
        ];

        if($data !== true) {
            $options['text'] = "Try to die later: ".json_encode($data);
            $this->telegram->sendMessage($options);
            return false;
        } else {
            $options['text'] = "You are officially dead, sins are forgiven";
            $this->telegram->sendMessage($options);
            return false;
        }



	}
}
