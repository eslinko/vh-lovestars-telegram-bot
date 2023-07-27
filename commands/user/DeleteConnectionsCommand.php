<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class DeleteConnectionsCommand.
 */
class DeleteConnectionsCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "delete_connections";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Delete connections";
	
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
        $data = $lcApi->makeRequest('get-user-connections', ['telegram_id' => $telegram_id]);

        $options = [
            'chat_id' => $telegram_id,
        ];

        if($data['status'] === 'error' || empty($data)) {
            $options['text'] = __("Error! Try again later.", $user['user']['language']);
            $this->telegram->sendMessage($options);
            return false;
        } else {
            if(empty($data['connections'])) {
                $options['text'] = __('You do not have connections', $user['user']['language']);
                $this->telegram->sendMessage($options);
            } else {
                $users_buttons=[];
                foreach ($data['connections'] as $item) {
                    $users_buttons[]=
                        [
                            Keyboard::inlineButton([
                                'text' => $item['username'].' created on '.date('j/m/y',strtotime($item['created_on'])),
                                'callback_data' => 'delete_connection_by_id__'.$item['connection_id']
                            ])
                        ];

                }
                $options['reply_markup'] = Keyboard::make([
                    'inline_keyboard' =>  $users_buttons,
                    'resize_keyboard' => true
                ]);
                $options['text'] = __('Delete connections:', $user['user']['language']);
                $this->telegram->sendMessage($options);
            }
        }



	}
}
