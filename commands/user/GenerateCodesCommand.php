<?php

namespace Telegram\Bot\Commands;

/**
 * Class GenerateCodesCommand.
 */
class GenerateCodesCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "generate_codes";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Generate invitation codes";
	
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

        if($user['user']['role'] !== 'admin') return false;

        $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __('How many codes do you need to generate?', $user['user']['language'])]);
        set_command_to_last_message($this->name, $telegram_id);
	}
}
