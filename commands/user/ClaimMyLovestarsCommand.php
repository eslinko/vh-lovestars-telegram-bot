<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class ClaimMyLovestarsCommand.
 */
class ClaimMyLovestarsCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "claim_my_lovestars";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Claim my Lovestars";
	
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

        $options['text'] = __('Please enter your magic code', $user['user']['language']);
        $this->telegram->sendMessage($options);
        set_command_to_last_message($this->name, $telegram_id);
	}
}
