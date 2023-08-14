<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class SetUserInterestsCommand.
 */
class SetUserInterestsCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "set_user_interests";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Set your interests";
	
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

/*        $options = [
            'chat_id' => $telegram_id,
        ];
		
		$options['text'] = __("Just imagine, all your pressing tasks are sorted out. You have more than enough money for any whim, and you enjoy complete harmony with your loved ones, knowing that everything is going well for them too. Your living conditions are exactly as you desire. Now, you have plenty of free time to spend on yourself. List them out (you can use commas or simply write them in a column): What activities would you engage in? Essentially, what actions would bring you joy and fulfilment (because let's face it, you wouldn't want to do things you don't enjoy)?", $user['user']['language']);
		
		$this->telegram->sendMessage($options);*/
        \TGKeyboard::hideKeyboard($telegram_id, $this->telegram, __("Just imagine, all your pressing tasks are sorted out. You have more than enough money for any whim, and you enjoy complete harmony with your loved ones, knowing that everything is going well for them too. Your living conditions are exactly as you desire. Now, you have plenty of free time to spend on yourself. List them out (you can use commas or simply write them in a column): What activities would you engage in? Essentially, what actions would bring you joy and fulfilment (because let's face it, you wouldn't want to do things you don't enjoy)?", $user['user']['language']));

        set_command_to_last_message($this->name, $telegram_id);
	}
}
