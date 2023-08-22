<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class SentInvitesCommand.
 */
class SentInvitesCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "sent_invites";
	
	/**
	 * @var string Command Description
	 */
	protected $description = "Sent invites";
	
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
        $data = $lcApi->makeRequest('get-user-sent-invites', ['telegram_id' => $telegram_id]);

        $options = [
            'chat_id' => $telegram_id,
        ];

        if($data['status'] === 'error' || empty($data)) {
            $options['text'] = __("Error! Try again later.", $user['user']['language']);
            $this->telegram->sendMessage($options);
            return false;
        } else {
            if(empty($data['connections'])) {
                $options['text'] = __('You do not have pending or rejected invites', $user['user']['language']);
            } else {
                $i=1;
                $pending_exist=false;
                foreach ($data['connections'] as $item) {
                    if($item['status']==='pending'){
                        $status = __('pending', $user['user']['language']);
                        $pending_exist=true;
                    }
                    else
                        $status = __('rejected', $user['user']['language']);
                    $user_name_text = $item['public_alias'];
                    if(!empty($item['telegram_alias']))$user_name_text.=' (@'.$item['telegram_alias'].')';
                    $options['text'].=$i.'. '.$user_name_text.' updated at  '.date('j/m/y',strtotime($item['updated_at'])).' - '.$status."\n";
                    $i++;
                }
                if($pending_exist==true){
                    $options['reply_markup'] = Keyboard::make([
                        'inline_keyboard' =>  [
                            [
                                Keyboard::inlineButton([
                                    'text' => __('Resend Invite', $user['user']['language']),
                                    'callback_data' => 'resend_pending_invites'
                                ]),
                            ]
                        ],
                        'resize_keyboard' => true
                    ]);
                }
            }

        }

		$this->telegram->sendMessage($options);
	}
}
