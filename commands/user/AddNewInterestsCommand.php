<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class MyConnectionsCommand.
 */
class AddNewInterestsCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "add_new_interests";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Add new interests";
	
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

        $this->telegram->sendMessage(['chat_id' => $telegram_id, 'text' => __("Type any new item in a separate message in order to...", $user['user']['language'])]);

        //\TGKeyboard::hideKeyboard($telegram_id, $this->telegram, __("Type any new item in a separate message in order to...", $user['user']['language']));
        set_command_to_last_message("my_interests_and_values", $telegram_id);
    }
}
