<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;
use TGKeyboard;

class ShowKeyboardCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "show_keyboard";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Show main keyboard";
	
	/**
	 * @inheritdoc
	 */
	public function handle()
	{
		$update = $this->getUpdate();

		$telegram_id = $update->getMessage()->chat->id;
        $user = user_is_verified($telegram_id);
        if(!$user['status']) {//unknown user, show start keyboard
            TGKeyboard::showStartKeyboard();
            return;
        }
        $user = $user['user'];

        TGKeyboard::showMainKeyboard($telegram_id, $this->telegram, $user, "Home");

	}
}