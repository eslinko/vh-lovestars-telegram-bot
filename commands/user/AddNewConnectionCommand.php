<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class MyConnectionsCommand.
 */
class AddNewConnectionCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "add_new_connection";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Add new connection";
	
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

        $options['text'] = __('Type Zeya platform alias or Telegram alias of a person you want to make a connection with.', $user['user']['language']);
        $this->telegram->sendMessage($options);

        //\TGKeyboard::hideKeyboard($telegram_id, $this->telegram, __('Type Zeya platform alias or Telegram alias of a person you want to make a connection with.', $user['user']['language']));
        set_command_to_last_message($this->name, $telegram_id);
    }
}
