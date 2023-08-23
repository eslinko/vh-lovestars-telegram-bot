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
                $i=1;
                foreach ($data['connections'] as $item) {
                    $user_name_text = $item['public_alias'];
                    if(!empty($item['telegram_alias']))$user_name_text.=' (@'.$item['telegram_alias'].')';
                    $users_buttons[]=
                        [
                            Keyboard::inlineButton([
                                'text' => $i.'. '.$user_name_text.' '.__('created on', $user['user']['language']).' '.date('j/m/y',strtotime($item['created_on'])),
                                'callback_data' => 'confirm_remove_connection_by_id__'.$item['connection_id'].'__'.$item['user_id']
                            ])
                        ];
                    $i++;
                }
                $options['reply_markup'] = Keyboard::make([
                    'inline_keyboard' =>  $users_buttons,
                    'resize_keyboard' => true
                ]);
                $options['text'] = __('Tap on the user you want to remove from your connections in the list below', $user['user']['language']);
                $this->telegram->sendMessage($options);
            }
        }



	}
}
