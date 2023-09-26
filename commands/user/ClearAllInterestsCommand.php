<?php

namespace Telegram\Bot\Commands;

/**
 * Class ClearAllInterestsCommand.
 */
class ClearAllInterestsCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "clear_all_interests";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Clear all interests";
	
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
        $lcApi->makeRequest('clear-all-interests', ['telegram_id' => $telegram_id]);
        $this->telegram->triggerCommand('my_interests_and_values', $update);
    }
}
