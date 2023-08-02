<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class RejectedInvitesCommand.
 */
class RejectedInvitesCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "rejected_invites";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Rejected invites";
	
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
        $data = $lcApi->makeRequest('get-user-rejected-invites', ['telegram_id' => $telegram_id]);

        $options = [
            'chat_id' => $telegram_id,
        ];

        if($data['status'] === 'error' || empty($data)) {
            $options['text'] = __("Error! Try again later.", $user['user']['language']);
            $this->telegram->sendMessage($options);
            return false;
        } else {
            if(empty($data['connections'])) {
                $options['text'] = __('You do not have rejected invites', $user['user']['language']);
                $this->telegram->sendMessage($options);
            } else {
                $users_buttons=[];
                $i=1;
                foreach ($data['connections'] as $item) {
                    //$options['text'].=$i.'. @'.$item['username'].' updated at  '.date('j/m/y',strtotime($item['updated_at']))."\n";
                    $users_buttons[]=
                        [
                            Keyboard::inlineButton([
                                'text' => $i.'. '.__('Accept', $user['user']['language']).' @'.$item['username'].' rejected at  '.date('j/m/y',strtotime($item['updated_at'])),
                                'callback_data' => 'ask_to_revert_connection__'.$user['user']['id'].'__'.$item['user_id']//'callback_data' => 'create_new_connection__'.$user['id']
                            ])
                        ];
                    $i++;
                }
                $options['reply_markup'] = Keyboard::make([
                    'inline_keyboard' =>  $users_buttons,
                    'resize_keyboard' => true
                ]);
                $options['chat_id'] = $telegram_id;
                $options['text'] = __('Rejected invites:', $user['user']['language']);
                $this->telegram->sendMessage($options);
            }

        }


    }
}
